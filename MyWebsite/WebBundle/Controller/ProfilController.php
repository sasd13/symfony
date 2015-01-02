<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProfilController extends Controller
{
	public function afficherAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
		$idProfil = $session->get('idProfil');
		if ($idProfil == null)
		{
			if ($request->getMethod() == 'POST') 
			{
				$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
				if(strcmp($request->request->get('password'), $admin->getPassword()) !== 0)
				{
					return $this->render('MyWebsiteWebBundle:Web:login.html.twig');
				}
			}
			else return $this->render('MyWebsiteWebBundle:Web:login.html.twig');
		}
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
		$session->set('idProfil', $profil->getId());
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->add('file')
			->getForm();
		
		$layout = 'profil-edit';				
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout, 
																				'profil' => $profil, 
																				'formPicture' => $formPicture->createView()
		));
	}
	
	public function modifierAction($displayPicture)
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->getForm();
		
		$message = "Les informations n'ont pas été modifiées";
		$formPicture->handleRequest($request);
		if($formPicture->isValid())
		{
			$em->persist($document);
			$em->flush();
			
			$message = "Les informations ont été modifiées avec succès";
		}
		
		return $this->redirect($this->generateUrl('web_profil_afficher', array('messagePucture' => $messagePicture)));
    }
	
	/**
	 * @Template()
	 */
	public function uploadAction()
	{
		$document = new Document();
		$form = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->getForm()
		;
		
		if ($this->getRequest()->isMethod('POST')) 
		{
			$form->handleRequest($request);
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
			
				$em->persist($document);
				$em->flush();

				$this->redirect($this->generateUrl('web_profil'));
			}
		}
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);	
		$layout = 'profil-edit';
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout, 
																				'profil' => $profil, 
																				'form' => $formPicture->createView()
		));
	}
	
	public function afficherAdministratorAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
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
	
	public function modifierAdministratorAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();		
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
		$formAdmin = $this->createFormBuilder($admin)
			->add('emailBackup', 'email')
			->add('password', 'password')
			->getForm();
			
		$message = "Les informations n'ont pas été modifiées";
		$formAdmin->handleRequest($request);
		
		$em->persist($admin);
		$em->flush();
		$message = "Les informations ont été modifiées avec succès";
		
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
