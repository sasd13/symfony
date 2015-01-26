<?php

namespace MyWebsite\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\ProfileBundle\Entity\Admin;

class AdminController extends Controller
{	
	public function loadAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idUser') == null)
		{
			if($request->getSession()->get('menuWeb') == null)
			{
				//Get¨MenuWeb mode Client
				$menuWeb = $this->container->get('web_generator')->generateMenu(array(
					$webData::DEFAULT_MENU_DISPLAY_WEB,
					$profileData::CLIENT_MENU_DISPLAY_WEB,
				));
				$request->getSession()->set('menuWeb', $menuWeb);
				//End getting
			}
		
			$admin = new Admin();
			
			$form = $this->createForm('profile_admin', $admin, array(
				'action' => $this->generateUrl($router::ROUTE_PROFILE_ADMIN)
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
					
					return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render($layouter::LAYOUT_PROFILE_USER_LOGIN, array(
				'title' => 'Administration',
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		if($request->getSession()->get('mode') !== 'admin')
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		//Get¨MenuWeb mode Admin
		$menuWeb = $this->container->get('web_generator')->generateMenu(array(
			$webData::DEFAULT_MENU_DISPLAY_WEB,
			$profileData::ADMIN_MENU_DISPLAY_WEB,
		));
		$request->getSession()->set('menuWeb', $menuWeb);
		//End getting
		
		//Get¨MenuProfile mode Admin
		$menuProfile = $this->container->get('web_generator')->generateMenu(array(
			$profileData::ADMIN_MENU_DISPLAY_PROFILE,
		));
		$request->getSession()->set('menuProfile', $menuProfile);
		//End getting
		
		$admin = $em->getRepository('MyWebsiteProfileBundle:Admin')->find($request->getSession()->get('idUser'));
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_PROFILE_ADMIN,
			'user' => $admin,
		));
	}
	
	public function editAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
		}
		
		$admin = $em->getRepository('MyWebsiteProfileBundle:Admin')->myFindWithCategoriesAndContents($request->getSession()->get('idUser'));
		
		$form = $this->createForm('profile_admin', $admin, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_ADMIN_INFO)
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$adminOld = $admin->copy();
			
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
						
						if($content->getId() === $contentOld->getIdCopy())
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
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_PROFILE_ADMIN_EDIT,
			'user' => $admin,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
}
