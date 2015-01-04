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
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->findOneByLogin("root");
		if($admin != null)
		{
			$formAdmin = $this->createFormBuilder($admin)
			->add('emailBackup', 'email')
			->add('password', 'password')
			->getForm();
		
			$layout = 'profil-admin-edit';
			return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																						'layout' => $layout,
																						'admin' => $admin,
																						'formAdmin' => $formAdmin->createView()
			));
		}
		
		return $this->redirect($this->generateUrl('web_profil_error'));
	}
	
	public function modifierAdministratorAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();		
		
		if($request->getSession()->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find($request->request->get('idAdmin'));
		if($admin != null)
		{
			$formAdmin = $this->createFormBuilder($admin)
				->add('emailBackup', 'email')
				->add('password', 'password')
				->getForm();
			$formAdmin->handleRequest($request);
			
			$message = "Les informations n'ont pas été enregistrées";
			if ($request->getSession()->get('idProfil') != null AND strcmp($admin->getPassword(), $request->request->get('confirmpassword')) === 0)
			{
				$em->persist($admin);
				$em->flush();
			
				$message = "Les informations ont été enregistrées avec succès";
			}
			
			$layout = 'profil-admin-edit';
			return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																						'layout' => $layout,
																						'admin' => $admin,
																						'formAdmin' => $formAdmin->createView(),
																						'message' => $message
			));
		}
		
		return $this->redirect($this->generateUrl('web_profil_error'));
    }
}
