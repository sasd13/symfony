<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Form\UserType;
use MyWebsite\WebBundle\Entity\Client;
use MyWebsite\WebBundle\Form\ClientType;

class UserController extends Controller
{
	public function signupAction()
	{
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Get¨MenuBar
		$menuBar = $this->container->get('web_generator')->generateMenu('menu_home', 'Client');
		$request->getSession()->set('menuBar', $menuBar);
		
		if($request->getSession()->get('idUser') != null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = new Client();
		
		$form = $this->createForm(new ClientType(), $client, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_USER_SIGNUP)
		));
			
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->submit($request->get($form->getName()), false);
			
			$message = "Informations érronées";
			
			$clientBuffer = $em->getRepository('MyWebsiteWebBundle:User')->findByLogin($client->getLogin());
			if($form->isValid()
				AND $clientBuffer == null
				AND $client->getPassword() === $request->request->get('confirmPassword'))
			{				
				//Try create Client with condition on email
				$client = $this->container->get('web_generator')->generateClient($client);
				if($client != null)
				{
					$request->getSession()->set('idUser', $client->getId());
					
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
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$user = $em->getRepository('MyWebsiteWebBundle:User')->find($request->getSession()->get('idUser'));
		
		$oldPassword = $user->getPassword();
		
		$form = $this->createForm(new UserType(), $user, array(
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
			'subLayout' => 'User/user',
			'user' => $user,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function logoutAction()
    {
		$router = $this->container->get('web_router');
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_HOME));
    }
	
	public function upgradeAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$user = $em->getRepository('MyWebsiteWebBundle:User')->find($request->getSession()->get('idUser'));
		$user->setPrivacyLevel(User::PRIVACYLEVEL_MEDIUM);
		
		$em->flush();
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
    }
	
	public function downgradeAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idUser') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_ADMIN));
		}
		
		$user = $em->getRepository('MyWebsiteWebBundle:User')->find($request->getSession()->get('idUser'));
		$user->setPrivacyLevel(User::PRIVACYLEVEL_LOW);
		
		$em->flush();
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
    }
}
