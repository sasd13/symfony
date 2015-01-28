<?php

namespace MyWebsite\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\ProfileBundle\Entity\Client;
use MyWebsite\WebBundle\Entity\Document;

class ClientController extends Controller
{
	public function loadAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		//Get¨MenuWeb mode Client
		$menuWeb = $this->container->get('web_generator')->generateMenu(array(
			$webData::DEFAULT_MENU_DISPLAY_WEB,
			$profileData::CLIENT_MENU_DISPLAY_WEB,
		));
		$request->getSession()->set('menuWeb', $menuWeb);
		//End getting
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idUser') == null)
		{
			$client = new Client();
			
			$form = $this->createForm('profile_client', $client, array(
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
				
				$clientBuffer = $em->getRepository('MyWebsiteProfileBundle:User')->findOneByLogin($client->getLogin());
				if ($form->isValid()
					AND $clientBuffer != null
					AND $clientBuffer->getPassword() === $client->getPassword())
				{
					$request->getSession()->set('idUser', $clientBuffer->getId());
					$request->getSession()->set('mode', 'client');
					
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
		
		if($request->getSession()->get('mode') !== 'client')
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
		}
		
		//Get¨Profile mode Client
		$menuProfile = $this->container->get('web_generator')->generateMenu(array(
			$profileData::CLIENT_MENU_DISPLAY_PROFILE,
		));
		$request->getSession()->set('menuProfile', $menuProfile);
		//End getting
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_PROFILE_CLIENT,
			'user' => $client,
		));
	}
	
	public function editAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$profileData = $this->container->get('profile_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->myFindWithCategoriesAndContents($request->getSession()->get('idUser'));
		
		$form = $this->createForm('profile_client', $client, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_CLIENT_INFO)
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$clientOld = $this->container->get('profile_copy')->getClientCopy($client);
			
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$updated = $this->container->get('profile_recorder')->updateClient($client, $clientOld);
				
				if($updated === true)
				{
					$message = "Les informations ont été enregistrées";
				}
			}
		}
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_PROFILE_CLIENT_EDIT,
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function editPictureAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$webData = $this->container->get('web_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->myFindWithCategoryAndPicture($request->getSession()->get('idUser'));
		$category = $client->getCategories()->get(0);
		$oldPicture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		$picture = new Document('image');
		$form = $this->createForm('web_document', $picture, 
			array('action' => $this->generateUrl($router::ROUTE_PROFILE_CLIENT_PICTURE)
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "La photo de profil n'a pas été enregistrée";
			
			if($form->isValid())
			{
				$picture->setCategory($category);
				$em->persist($picture);
				
				if($picture->getPath() !== $webData::DEFAULT_DOCUMENT_PATH)
				{
					if($oldPicture != null)
					{
						$em->remove($oldPicture);
					}
					$category->update();
					
					$client->setPicturePath($picture->getPath());
					$client->setPictureTitle($picture->getTitle());
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
			'subLayout' => $layouter::SUBLAYOUT_PROFILE_CLIENT_PICTURE_EDIT,
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message
		));
    }
	
	public function deletePictureAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('profile_router');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->myFindWithCategoryAndPicture($request->getSession()->get('idUser'));
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
		//Services
		$router = $this->container->get('profile_router');
		//End services
		
		return $this->redirect($this->generateUrl($router::ROUTE_WEB_EXCEPTION_ERROR));
	}
}
