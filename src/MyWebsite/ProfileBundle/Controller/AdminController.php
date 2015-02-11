<?php

namespace MyWebsite\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\ProfileBundle\Entity\Admin;

class AdminController extends Controller
{	
	public function homeAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idUser') == null)
		{
			$this->container->get('profile_menuGenerator')->setWebMenu();
		
			$admin = new Admin();
			
			$form = $this->createForm('profile_admin', $admin, array(
				'action' => $this->generateUrl('profile_admin_home')
			));
			
			$message = "* Denotes Required Field";
			
			if($request->getMethod() === 'POST')
			{
				/*
				 * With method handleRequest, the request submit values of all fields present in data even they are missed in form
				 * All missed field have null value, so the form will not validate them with their own assert rules
				 *
				 * This method is used to submit the form in clearing the field that are not present in data
				 * See method submit of FormInterface in Symfony doc
				 */
				$form->submit($request->get($form->getName()), false);
				
				$adminBuffer = $em->getRepository('MyWebsiteProfileBundle:User')->findOneByLogin($admin->getLogin());
				if ($form->isValid()
					AND $adminBuffer != null
					AND $adminBuffer->getPassword() === $admin->getPassword()
					AND ($adminBuffer->getPrivacyLevel() === $profileData::USER_PRIVACYLEVEL_MEDIUM
						OR $adminBuffer->getPrivacyLevel() === $profileData::USER_PRIVACYLEVEL_HIGH))
				{
					$request->getSession()->set('idUser', $adminBuffer->getId());
					$request->getSession()->set('mode', 'admin');
					
					return $this->redirect($this->generateUrl('profile_admin_home'));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render('MyWebsiteProfileBundle:User:login.html.twig', array(
				'title' => 'Administration',
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		if($request->getSession()->get('mode') !== 'admin')
		{
			return $this->redirect($this->generateUrl('profile_client_home'));
		}

		$this->container->get('profile_menuGenerator')->setWebMenu();
		$this->container->get('profile_menuGenerator')->setProfileMenu();
		
		$admin = $em->getRepository('MyWebsiteProfileBundle:Admin')->find($request->getSession()->get('idUser'));
		
		return $this->render('MyWebsiteProfileBundle::profile.html.twig', array(
			'subLayout' => 'Admin:admin-home',
			'user' => $admin,
		));
	}
	
	public function editAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_admin_home'));
		}
		
		$admin = $em->getRepository('MyWebsiteProfileBundle:Client')->myFindWithCategoriesAndContents($request->getSession()->get('idUser'));
		
		$form = $this->createForm('profile_client', $admin, array(
			'action' => $this->generateUrl('profile_admin_edit')
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$adminOld = clone $admin;
			
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$categories = $admin->getCategories();
				foreach($categories as $keyCategory => $category)
				{
					$contents = $category->getContents();
					foreach($contents as $keyContent => $content)
					{
						$contentOld = $adminOld
							->getCategories()
							->get($keyCategory)
							->getContents()
							->get($keyContent)
						;
						
						if($content->getId() === $contentOld->getId())
						{
							if($content->getFormType() === 'textarea')
							{
								if($content->getTextValue() !== $contentOld->getTextValue())
								{
									$category->update();
								}
							}
							else
							{
								//Compare values only, not types
								if($content->getStringValue() != $contentOld->getStringValue())
								{
									$category->update();
								}
							}
						}
							
						if($content->getLabel() === $profileData::USER_CONTENT_LABEL_FIRSTNAME
							AND $content->getStringValue() !== $admin->getFirstName())
						{
							$admin->setFirstName($content->getStringValue());
							$admin->update();
						}
						
						if($content->getLabel() === $profileData::USER_CONTENT_LABEL_LASTNAME
							AND $content->getStringValue() !== $admin->getLastName())
						{
							$admin->setLastName($content->getStringValue());
							$admin->update();
						}
						
						if($content->getLabel() === $profileData::USER_CONTENT_LABEL_EMAIL
							AND $content->getStringValue() !== $admin->getEmail())
						{
							$admin->setEmail($content->getStringValue());
							$admin->update();
						}
					}
				}
				
				$em->flush();
				
				$message = "Les informations ont été enregistrées";
			}
		}
		
		return $this->render('MyWebsiteProfileBundle::profile.html.twig', array(
			'subLayout' => 'Admin:admin-edit',
			'user' => $admin,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
}
