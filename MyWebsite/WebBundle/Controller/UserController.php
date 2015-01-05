<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Entity\Profile;

class UserController extends Controller
{
	public function editUserAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		$user = $profile->getUser();
		if($user != null)
		{
			$formUser = $this->createFormBuilder($user)
				->setAction($this->generateUrl('web_profile_user_edit'))
				->setMethod('POST')
				->add('login', 'text')
				->add('password', 'password')
				->getForm();
			
			$message = "* Denotes Required Field";
			
			if($request->getMethod() == 'POST')
			{
				$formUser->handleRequest($request);
			
				$message = "Les informations n'ont pas été enregistrées";
				if(strcmp($user->getPassword(), $request->request->get('confirmpassword')) === 0)
				{
					$em->persist($user);
					$em->flush();
				
					$message = "Les informations ont été enregistrées avec succès";
				}
				$user = $em->getRepository('MyWebsiteWebBundle:User')->find($user->getId());
			}
			
			$layout = 'profile-user-edit';
			return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																						'layout' => $layout,
																						'user' => $user,
																						'formUser' => $formUser->createView(),
																						'message' => $message
			));
		}
		
		return $this->redirect($this->generateUrl('web_error'));
	}
	
	public function edUserAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('login') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$user = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($request->getSession()->get('login'));
		if($user != null)
		{
			$formUser = $this->createFormBuilder($user)
				->add('login', 'text')
				->add('password', 'password')
				->getForm();
			$formUser->handleRequest($request);
			
			$message = "Les informations n'ont pas été enregistrées";
			if ($request->getSession()->get('login') != null AND strcmp($user->getPassword(), $request->request->get('confirmpassword')) === 0)
			{
				$em->persist($user);
				$em->flush();
			
				$message = "Les informations ont été enregistrées avec succès";
			}
			
			$layout = 'profile-user-edit';
			return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																						'layout' => $layout,
																						'user' => $user,
																						'formUser' => $formUser->createView(),
																						'message' => $message
			));
		}
		
		return $this->redirect($this->generateUrl('web_error'));
    }
}
