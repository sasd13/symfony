<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use MyWebsite\WebBundle\Entity\Profil;

class AdministratorController extends Controller
{
	public function afficherAdministratorAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
		if($session->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		else
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
			$formAdmin = $this->createFormBuilder($admin)
				->add('emailBackup', 'email')
				->add('password', 'password')
				->getForm();
		
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);		
			$layout = 'profil-admin-edit';				
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil,
																					'formAdmin' => $formAdmin->createView()
			));
		}
	}
	
	public function modifierAdministratorAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();		
		
		if($session->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		else
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
			$formAdmin = $this->createFormBuilder($admin)
				->add('emailBackup', 'email')
				->add('password', 'password')
				->getForm();
			
			$message = "Les informations n'ont pas été modifiées";
			$formAdmin->handleRequest($request);
			if ($session->get('idProfil') != null AND strcmp($request->request->get('password'), $request->request->get('confirmpassword')) === 0)
			{
				$em->persist($admin);
				$em->flush();
				$message = "Les informations ont été modifiées avec succès";
			}
		
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);		
			$layout = 'profil-admin-edit';	
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil,
																					'formAdmin' => $formAdmin->createView(),
																					'message' => $message
			));
		}
    }
}
