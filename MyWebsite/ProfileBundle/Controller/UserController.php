<?php

namespace MyWebsite\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MyWebsite\ProfileBundle\Entity\User;
use MyWebsite\ProfileBundle\Entity\Client;

class UserController extends Controller
{
	public function signupAction()
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		//Check Session
		if($request->getSession()->get('idUser') != null)
		{
			if($request->getSession()->get('mode') === 'admin')
			{
				return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
			}
			else
			{
				return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
			}
		}
		//End checking
		
		//Get¨MenuWeb mode Client
		$menuWeb = $this->container->get('web_generator')->generateMenu(array(
			$webData::DEFAULT_MENU_DISPLAY_WEB,
			$profileData::CLIENT_MENU_DISPLAY_WEB,
		));
		$request->getSession()->set('menuWeb', $menuWeb);
		//End getting
		
		$client = new Client();
		
		$form = $this->createForm('profile_client', $client, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_USER_SIGNUP)
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
				$client = $this->container->get('profile_generator')->generateClient($client);
				if($client != null)
				{
					$request->getSession()->set('idUser', $client->getId());
					$request->getSession()->set('mode', 'client');
					
					return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
				}
				
				$message = "Email indisponible";
			}
		}	
		
		return $this->render($layouter::LAYOUT_PROFILE_USER_SIGNUP, array(
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function loadAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('profile_router');
		$layouter = $this->container->get('profile_layouter');
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$user = $em->getRepository('MyWebsiteProfileBundle:User')->find($request->getSession()->get('idUser'));
		
		$oldPassword = $user->getPassword();
		
		$form = $this->createForm('profile_user', $user, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_USER)
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
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_PROFILE_USER,
			'user' => $user,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function logoutAction()
    {
		$router = $this->container->get('web_router');
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_WEB_HOME));
    }
	
	public function upgradeAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('profile_router');
		$profileData = $this->container->get('profile_data');
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$user = $em->getRepository('MyWebsiteProfileBundle:User')->find($request->getSession()->get('idUser'));
		$user->setPrivacyLevel($profileData::USER_PRIVACYLEVEL_MEDIUM);
		
		$em->flush();
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
    }
	
	public function downgradeAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('profile_router');
		$profileData = $this->container->get('profile_data');
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
		}
		
		$user = $em->getRepository('MyWebsiteProfileBundle:User')->find($request->getSession()->get('idUser'));
		$user->setPrivacyLevel($profileData::USER_PRIVACYLEVEL_LOW);
		
		$em->flush();
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
    }
}
