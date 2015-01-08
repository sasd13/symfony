<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Admin;
use MyWebsite\WebBundle\Entity\Category;
use \DateTime;

class AdminController extends Controller
{
	public function loadAdminAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $em->getRepository('MyWebsiteWebBundle:ModuleHandler')->myFindOrdered();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl('web_error'));
		}
		$request->getSession()->set('modules', $modules);
		
		$admin = null;
		if($request->getSession()->get('idAdmin') == null)
		{
			$user = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($request->request->get('login'));
			if ($request->getMethod() == 'POST' AND $user != null AND strcmp($request->request->get('password'), $user->getPassword()) === 0 AND $user->getPrivacyLevel() == 1)
			{
				$admin = $em->getRepository('MyWebsiteWebBundle:Admin')->findOneByUser($user);
				$request->getSession()->set('idAdmin', $admin->getId());
			}
			else
			{
				return $this->render('MyWebsiteWebBundle:Admin:login.html.twig');
			}
		}
		else 
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Admin')->find($request->getSession()->get('idAdmin'));
		}
		
		if($admin != null)
		{
			$formAdmin = $this->createFormBuilder($admin)
				->add('email', 'email', array('required' => false))
				->getForm();
			
			$arrayOfFormsViewsCategories = null;
			$arrayOfArraysOfFormsViewsContents = null;
			
			//creer myFindCategories
			$categories = $em->getRepository('MyWebsiteWebBundle:Category')->findByTimeManager($admin->getTimeManager()->getId());
			if($categories != null)
			{
				$arrayOfFormsViewsContents = null;
				
				foreach($categories as $category)
				{
					$arrayOfFormsViewsCategories[] = $this->createFormBuilder($category)
						->add('title', 'text', array('required' => false))
						->add('tag', 'text', array('required' => false))
						->getForm()
						->createView();
					
					$contents = $category->getContents();
					foreach($contents as $key => $content)
					{
						$arrayOfFormsViewsContents[] = $this->createFormBuilder($content)
							->add('title', 'text', array('required' => false))
							->add('tag', 'text', array('required' => false))
							->getForm()
							->createView();
					}
				}
			}
			
			return $this->render('MyWebsiteWebBundle:Admin:admin.html.twig', array(
																						'layout' => 'admin-edit',
																						'admin' => $admin,
																						'formAdmin' => $formAdmin->createView(),
																						'categories' => $categories,
																						'formsViewsCategories' => $arrayOfFormsViewsCategories
			));
		}
		
		return $this->redirect($this->generateUrl('web_error'));
	}
	
	public function editAdminAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idAdmin') == null)
		{
			return $this->redirect($this->generateUrl('web_admin'));
		}
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Admin')->find($request->getSession()->get('idAdmin'));
		if($admin != null)
		{
			$formAdmin = $this->createFormBuilder($admin)
				->add('email', 'email')
				->getForm();
			$formAdmin->handleRequest($request);
			
			$category = $em->getRepository('MyWebsiteWebBundle:Category')->find($request->request->get('idCategory'));
			if($category != null)
			{
				$formCategory = $this->createFormBuilder($category)
					->add('title', 'text')
					->add('tag', 'text')
					->getForm();
				$formCategory->handleRequest($request);
				
				$message = "Les informations n'ont pas été enregistrées";
				if($request->getSession()->get('idAdmin') != null)
				{
					$admin->getTimeManager()->setUpdateTime(new DateTime());
					$em->persist($admin);
					$em->persist($category);
					$em->flush();
					
					$message = "Les informations ont été enregistrées avec succès";
				}
		
				$admin = $em->getRepository('MyWebsiteWebBundle:Admin')->find($request->getSession()->get('idAdmin'));
				return $this->render('MyWebsiteWebBundle:Admin:admin.html.twig', array(
																							'layout' => 'admin-edit', 
																							'admin' => $admin, 
																							'category' => $category,
																							'formAdmin' => $formAdmin->createView(),
																							'formCategory' => $formCategory->createView(),
																							'message' => $message
				));
			}
		}
		
		return $this->redirect($this->generateUrl('web_error'));
    }
	
	public function logoutAction()
    {
		if ($this->getRequest()->getSession()->get('idAdmin') != null) $this->getRequest()->getSession()->remove('idAdmin');
		
        return $this->redirect($this->generateUrl('web_home'));
    }
}
