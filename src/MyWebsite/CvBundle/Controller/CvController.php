<?php

namespace MyWebsite\CvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\CvBundle\Entity\Cv;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

class CvController extends Controller
{
	public function listAction()
	{
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		$client = ($request->getSession()->get('idUser') != null)
			? $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'))
			: null
		;
		
		$cvs = ($client != null) 
			? $em->getRepository('MyWebsiteCvBundle:Cv')->findByClient($client)
			: $em->getRepository('MyWebsiteCvBundle:Cv')->findAll()
		;
		
		return $this->render('MyWebsiteCvBundle::cv-list.html.twig', array(
			'cvs' => $cvs
		));
	}
	
	public function loadAction($idCv)
	{
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->find($idCv);
		
		if($cv == null)
		{
			$router = $this->container->get('cv_router');
			return $this->redirect($this->generateUrl($router::ROUTE_CV_LIST));
		}
		
		return $this->render('MyWebsiteCvBundle::cv-load.html.twig', array(
			'cv' => $cv
		));
	}
	
	public function profileListAction()
	{
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		$cvs = $em->getRepository('MyWebsiteCvBundle:Cv')->findByClient($client);
		
		return $this->render('MyWebsiteCvBundle:Profile:cv.html.twig', array(
			'subLayout' => 'SubLayout/cv-list',
			'user' => $client,
			'cvs' => $cvs
		));
	}
	
	public function profileNewAction()
	{
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		$cv = new Cv();
		$form = $this->createForm('cv_cv', $cv, array(
			'action' => $this->generateUrl('cv_profile_new')
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$cv->setClient($client);
				$cv = $this->container->get('cv_recorder')->createCv($cv);
				
				if($cv != null)
				{
					return $this->redirect($this->generateUrl('cv_profile_edit', array(
						'idCv' => $cv->getId()
					)));
				}				
			}
		}
		
		return $this->render('MyWebsiteCvBundle:Profile:cv.html.twig', array(
			'subLayout' => 'SubLayout/cv-new',
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function profileEditAction($idCv)
	{
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		$data =  $this->container->get('cv_data');
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_client_home'));
		}
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->myFindWithCategoriesAndContents($idCv);
		
		if($cv == null)
		{
			return $this->redirect($this->generateUrl('cv_profile_list'));
		}
		
		//New category
		$category = new Category('content');
		$category
			->setTitle('New category')
			->setModuleEntity($cv)
		;
		$cv->addCategory($category);
		
		$content = new Content('langue', 'text');
		$content
			->setCategory($category)
			->setPlaceholder('anglais')
		;
		$category->addContent($content);
		$cv->addCategory($category);
		
		//Adding lines
		foreach($cv->getCategories() as $key => $category)
		{
			if($key >= 1)
			{
				for($i=0; $i<1; $i++)
				{
					$content = new Content('langue', 'text');
					$content
						->setCategory($category)
						->setPlaceholder('anglais')
					;
					$category->addContent($content);
					$cv->addCategory($category);
				}
			}
		}
		
		$form = $this->createForm('cv_cv', $cv, array(
			'action' => $this->generateUrl('cv_profile_edit', array(
				'idCv' => $cv->getId()
			))
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$cvOld = clone $cv;
			
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$updated = $this->container->get('cv_recorder')->updateCv($cv, $cvOld);
				
				if($updated === true)
				{
					$message = "Les informations ont été enregistrées avec succès";
				}
			}
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		return $this->render('MyWebsiteCvBundle:Profile:cv.html.twig', array(
			'subLayout' => 'SubLayout/cv-edit',
			'user' => $client,
			'cv' => $cv,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function profileActiveAction($idCv, $active)
	{
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('cv_router');
		//End services
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->myFindWithCategoryInfo($idCv);
		if($cv != null)
		{
			$cv->setActive($active);
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
	}
	
	public function profileDeleteAction($idCv)
	{
	
	}
	
	public function profileCategoryDeleteAction($idCategory)
	{
	
	}
	
	public function profileContentDeleteAction($idContent)
	{
	
	}
}