<?php

namespace MyWebsite\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\ProfileBundle\Entity\User;
use MyWebsite\ProfileBundle\Entity\Client;

class UserController extends Controller
{
	public function signupAction()
	{
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		//Check Session
		if($request->getSession()->get('idUser') != null)
		{
			if($request->getSession()->get('mode') === 'admin')
			{
				return $this->redirect($this->generateUrl('profile_admin_home'));
			}
			else
			{
				return $this->redirect($this->generateUrl('profile_client_home'));
			}
		}
		//End checking

		$this->container->get('profile_menuGenerator')->setWebMenu();
		
		$client = new Client();
		
		$form = $this->createForm('profile_client', $client, array(
			'action' => $this->generateUrl('profile_user_signup')
		));
			
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->submit($request->get($form->getName()), false);
			
			$message = "Informations érronées";
			
			$clientBuffer = $em->getRepository('MyWebsiteProfileBundle:User')->findByLogin($client->getLogin());
			if($form->isValid()
				AND $clientBuffer == null
				AND $client->getPassword() === $request->request->get('confirmPassword'))
			{				
				//Try create Client with condition on email
				$client = $this->container->get('profile_recorder')->createClient($client);
				if($client != null)
				{
					$request->getSession()->set('idUser', $client->getId());
					$request->getSession()->set('mode', 'client');
					
					return $this->redirect($this->generateUrl('profile_client_home'));
				}
				
				$message = "Email indisponible";
			}
		}	
		
		return $this->render('MyWebsiteProfileBundle:User:signup.html.twig', array(
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function editAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_client_home'));
		}
		
		$user = $em->getRepository('MyWebsiteProfileBundle:User')->find($request->getSession()->get('idUser'));
		
		$oldPassword = $user->getPassword();
		
		$form = $this->createForm('profile_user', $user, array(
			'action' => $this->generateUrl('profile_user_edit')
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid()
				AND $oldPassword === $request->request->get('oldPassword')
				AND $user->getPassword() === $request->request->get('confirmPassword'))
			{
				$user->update();
				
				$em->flush();
		
				$message = "Les informations ont été enregistrées avec succès";
			}
		}
		
		return $this->render('MyWebsiteProfileBundle::profile.html.twig', array(
			'subLayout' => 'User:user-edit',
			'user' => $user,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function logoutAction()
    {
		$this->container->get('request')->getSession()->clear();
		$this->container->get('profile_menuGenerator')->setWebMenu();

        return $this->redirect($this->generateUrl('web_home'));
    }
	
	public function upgradeAction()
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
		
		$user = $em->getRepository('MyWebsiteProfileBundle:User')->find($request->getSession()->get('idUser'));
		$user->setPrivacyLevel($profileData::USER_PRIVACYLEVEL_MEDIUM);
		
		$em->flush();
		
		$request->getSession()->clear();
		
        return $this->redirect($this->generateUrl('profile_admin_home'));
    }
	
	public function downgradeAction()
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$profileData = $this->container->get('profile_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl('profile_admin_home'));
		}
		
		$user = $em->getRepository('MyWebsiteProfileBundle:User')->find($request->getSession()->get('idUser'));
		$user->setPrivacyLevel($profileData::USER_PRIVACYLEVEL_LOW);
		
		$em->flush();
		
		$request->getSession()->clear();
		
        return $this->redirect($this->generateUrl('profile_client_home'));
    }
}
