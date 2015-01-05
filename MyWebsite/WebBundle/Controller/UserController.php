<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Entity\Profile;

class UserController extends Controller
{
	public function loadUserAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		$user = $profile->getUser();
		
		$formUser = $this->createFormBuilder($user)
			->setAction($this->generateUrl('web_profile_user'))
			->setMethod('POST')
			->add('login', 'text')
			->add('password', 'password')
			->getForm();
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() == 'POST')
		{
			$formUser->handleRequest($request);
		
			$message = "Les informations n'ont pas été enregistrées";
			if($formUser->isValid() AND strcmp($user->getPassword(), $request->request->get('confirmpassword')) === 0)
			{
				$em->persist($user);
				$em->flush();
		
				$message = "Les informations ont été enregistrées avec succès";
			}
			$user = $em->getRepository('MyWebsiteWebBundle:User')->find($user->getId());
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																					'layout' => 'profile-user-edit',
																					'user' => $user,
																					'formUser' => $formUser->createView(),
																					'message' => $message
		));
	}
}
