<?php

namespace MyWebsite\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\ProfileBundle\Entity\Admin;
use MyWebsite\ProfileBundle\Form\AdminType;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;
use MyWebsite\WebBundle\Form\CategoryType;
use MyWebsite\WebBundle\Form\ContentType;
use MyWebsite\WebBundle\Form\DocumentType;

class AdminController extends Controller
{	
	public function loadAction()
    {
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		/*
		 * Check Bundle and Module
		 */
		$controller = $request->attributes->get('_controller');
		// will get name\nameBundle\Controller\nameController::nameAction
		
		$check = $this->container->get('web_moduleHandler')->checkHandler($controller);
			
		if($check === false)
		{
			$router = $this->container->get('web_router');
			
			return $this->redirect($this->generateUrl($router::ROUTE_WEB_HOME));
		}
			
		//Get¨MenuBar
		$menuBar = $this->container->get('profile_generator')->generateMenu('menu_bar', 'Admin');
		$request->getSession()->set('menuBar', $menuBar);
		
		//Get¨MenuAdmin
		$menuAdmin = $this->container->get('profile_generator')->generateMenu('menu_admin');
		$request->getSession()->set('menuProfile', $menuAdmin);
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idUser') == null)
		{
			//Get¨MenuBar
			$menuBar = $this->container->get('web_generator')->generateMenu();
			$request->getSession()->set('menuBar', $menuBar);
		
			$admin = new Admin();
			
			$form = $this->createForm(new AdminType(), $admin, array(
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
					AND ($adminBuffer->getPrivacyLevel() === Admin::PRIVACYLEVEL_MEDIUM
						OR $adminBuffer->getPrivacyLevel() === Admin::PRIVACYLEVEL_HIGH))
				{
					$request->getSession()->set('idUser', $adminBuffer->getId());
					
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
		
		$admin = $em->getRepository('MyWebsiteProfileBundle:Admin')->find($request->getSession()->get('idUser'));
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Admin:admin',
			'user' => $admin,
		));
	}
	
	public function editAction()
    {
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
		}
		
		$admin = $em->getRepository('MyWebsiteProfileBundle:Admin')->myFindWithCategoriesAndContents($request->getSession()->get('idUser'));
		
		$form = $this->createForm(new AdminType(), $admin, array(
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
							
						if($content->getLabel() === Data::CONTENT_LABEL_ADMIN_FIRSTNAME
							AND $content->getStringValue() !== $admin->getFirstName())
						{
							$admin->setFirstName($content->getStringValue());
							$admin->update();
						}
						
						if($content->getLabel() === Data::CONTENT_LABEL_ADMIN_LASTNAME
							AND $content->getStringValue() !== $admin->getLastName())
						{
							$admin->setLastName($content->getStringValue());
							$admin->update();
						}
						
						if($content->getLabel() === Data::CONTENT_LABEL_USER_EMAIL
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
			'subLayout' => 'Admin:admin-edit',
			'user' => $admin,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
}