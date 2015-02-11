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
	public function homeAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		$this->container->get('profile_menuGenerator')->setWebMenu();
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idUser') == null)
		{
			$client = new Client();
			
			$form = $this->createForm('profile_client', $client, array(
				'action' => $this->generateUrl('profile_client_home')
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
					
					return $this->redirect($this->generateUrl('profile_client_home'));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render('MyWebsiteProfileBundle:User:login.html.twig', array(
				'title' => 'MyProfile',
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		if($request->getSession()->get('mode') !== 'client')
		{
			return $this->redirect($this->generateUrl('profile_admin_home'));
		}

		$this->container->get('profile_menuGenerator')->setProfileMenu();
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		return $this->render('MyWebsiteProfileBundle::profile.html.twig', array(
			'subLayout' => 'Client:client-home',
			'user' => $client,
		));
	}
	
	public function editAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$profileData = $this->container->get('profile_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_client_home'));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->myFindWithCategoriesAndContents($request->getSession()->get('idUser'));
		
		$form = $this->createForm('profile_client', $client, array(
			'action' => $this->generateUrl('profile_client_edit')
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$clientOld = clone $client;
			
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
		
		return $this->render('MyWebsiteProfileBundle::profile.html.twig', array(
			'subLayout' => 'Client:client-edit',
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function editPictureAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$webData = $this->container->get('web_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_client_home'));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->myFindWithCategoryAndPicture($request->getSession()->get('idUser'));
		$category = $client->getCategories()->get(0);
		$oldPicture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		$picture = new Document('image');
		$form = $this->createForm('web_document', $picture, 
			array('action' => $this->generateUrl('profile_client_picture_edit')
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
		
		return $this->render('MyWebsiteProfileBundle::profile.html.twig', array(
			'subLayout' => 'Client:client-picture-edit',
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message
		));
    }
	
	public function deletePictureAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_client_home'));
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
		
		return $this->redirect($this->generateUrl('profile_client_picture_edit'));
	}
	
	public function deleteAction()
    {
		return $this->redirect($this->generateUrl('web_exception_error'));
	}
}
