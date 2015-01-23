<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\Client;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;
use MyWebsite\WebBundle\Form\ClientType;
use MyWebsite\WebBundle\Form\CategoryType;
use MyWebsite\WebBundle\Form\ContentType;
use MyWebsite\WebBundle\Form\DocumentType;

class ClientController extends Controller
{
	public function loadAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$this->container->get('web_moduleHandler')->checkModules();
		
		$request->attributes->get('_controller');
		// will get name\nameBundle\Controller\nameController::nameAction
		
		$params = explode('::',$request->attributes->get('_controller'));
		// $params[0] = 'nameBundle\Controller\nameController';
		
		$params = explode('\\',$params[0]);
		// $params[3] = 'nameController';
		
		$controllerName = substr($params[3],0,-10);
		// $actionName = 'name';
		
		$module = $em->getRepository('MyWebsiteWebBundle:Module')->findOneByName($controllerName);
			
		if($module == null
			OR $module->getActive() === false)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_HOME));
		}
			
		//Get¨MenuBar
		$menuBar = $this->container->get('web_generator')->generateMenu('menu_home', 'Client');
		$request->getSession()->set('menuBar', $menuBar);
		
		//Get¨MenuClient
		$menuClient = $this->container->get('web_generator')->generateMenu('menu_client');
		$request->getSession()->set('menuProfile', $menuClient);
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idUser') == null)
		{
			$client = new Client();
			
			$form = $this->createForm(new ClientType(), $client, array(
				'action' => $this->generateUrl($router::ROUTE_PROFILE_CLIENT)
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
				
				$clientBuffer = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($client->getLogin());
				if ($form->isValid()
					AND $clientBuffer != null
					AND $clientBuffer->getPassword() === $client->getPassword())
				{
					$request->getSession()->set('idUser', $clientBuffer->getId());
					
					return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render($layouter::LAYOUT_PROFILE_USER_LOGIN, array(
				'title' => 'MyProfile',
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		$client = $em->getRepository('MyWebsiteWebBundle:Client')->find($request->getSession()->get('idUser'));
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Client/client',
			'user' => $client,
		));
	}
	
	public function editAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteWebBundle:Client')->myFindWithCategoriesAndContents($request->getSession()->get('idUser'));
		
		$form = $this->createForm(new ClientType(), $client, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_CLIENT_INFO)
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$clientOld = $client->copy();
			
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$categories = $client->getCategories();
				foreach($categories as $keyCategory => $category)
				{
					$contents = $category->getContents();
					foreach($contents as $keyContent => $content)
					{
						$contentOld = $clientOld
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
							
						if($content->getLabel() === Content::LABEL_CLIENT_FIRSTNAME
							AND $content->getStringValue() !== $client->getFirstName())
						{
							$client->setFirstName($content->getStringValue());
							$client->update();
						}
						
						if($content->getLabel() === Content::LABEL_CLIENT_LASTNAME
							AND $content->getStringValue() !== $client->getLastName())
						{
							$client->setLastName($content->getStringValue());
							$client->update();
						}
						
						if($content->getLabel() === Content::LABEL_USER_EMAIL
							AND $content->getStringValue() !== $client->getEmail())
						{
							$client->setEmail($content->getStringValue());
							$client->update();
						}
					}
				}
				
				$em->flush();
				
				$message = "Les informations ont été enregistrées";
			}
		}
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Client/client-edit',
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function editPictureAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteWebBundle:Client')->myFindWithCategoryAndPicture($request->getSession()->get('idUser'));
		$category = $client->getCategories()->get(0);
		$oldPicture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		$form = null;
		$picture = null;
		if($oldPicture == null)
		{
			$picture = new Document('image');
			$form = $this->createForm(new DocumentType(), $picture, array('action' => $this->generateUrl($router::ROUTE_PROFILE_CLIENT_PICTURE)));
		}
		else
		{
			$picture = $oldPicture;
			$form = $this->createForm(new DocumentType(), $picture, array('action' => $this->generateUrl($router::ROUTE_PROFILE_CLIENT_PICTURE)));
		}
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "La photo de profil n'a pas été enregistrée";
			
			if($form->isValid())
			{
				$picture->setCategory($category);
				$em->persist($picture);
				
				//die(var_dump($picture->getPath()));
				
				if($picture->getPath() !== Document::DEFAULT_PATH)
				{
					if($oldPicture != null)
					{
						$em->remove($oldPicture);
					}
					$category->update();
					
					$client->setPictureTitle($picture->getTitle());
					$client->setPicturePath($picture->getPath());
					$client->update();
					
					$message = "La photo de profil a été enregistrée avec succès";
				}
				else
				{
					$em->remove($picture);
				}
				
				$em->flush();
			}
		}
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Client/client-picture-edit',
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message
		));
    }
	
	public function deletePictureAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteWebBundle:Client')->myFindWithCategoryAndPicture($request->getSession()->get('idUser'));
		$category = $client->getCategories()->get(0);
		$picture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		if(($picture != null) AND is_file($picture->getAbsolutePath()))
		{
			$client->setPicturePath(null);
			$client->setPictureTitle(null);
			
			$category->removeDocument($picture);
			$category->update();
			
			$em->remove($picture);
			
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT_PICTURE));
	}
	
	public function deleteAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		return $this->redirect($this->generateUrl($router::ROUTE_ERROR));
	}
}
