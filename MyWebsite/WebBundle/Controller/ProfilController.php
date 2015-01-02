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
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
		if ($request->getMethod() == 'POST')
		{
			$session->set('idProfil', $profil->getId());
		}
		else 
		{
			$idProfil = $session->get('idProfil');
			if ($idProfil == null) return $this->redirect($this->generateUrl('mywebsiteweb_login'));
		}
		
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
		
		$messagePicture = "La photo n'a pas pu &eacyte;t&eacute; modifi&eacute;e";
		if($request->getMethod() == 'POST')
		{
			$formPicture->handleRequest($request);
			if($formPicture->isValid())
			{
				$em->persist($document);
				$em->flush();
				
				$messagePicture = "La photo a &eacyte;t&eacute; modifi&eacute; avec succ&egrave;";
			}
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
			->setAction($this->generateUrl('web_profil_admin_modifier'))
			->add('emailBackup', 'email', array('required' => false))
			->add('password', 'password', array('required' => false))
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
		
		$admin = new Admin();
		$formAdmin = $this->createFormBuilder($admin)
			->add('emailBackup', 'email')
			->add('password', 'password')
			->getForm();
		
		$message = "Le mot de passe n'a pas pu &eacyte;t&eacute; modifi&eacute;";
		if($request->getMethod() == 'POST')
		{
			$formAdmin->handleRequest($request);
			if($formAdmin->isValid())
			{
				$em->persist($admin);
				$em->flush();
				
				$message = "Le mot de passe a &eacyte;t&eacute; modifi&eacute; avec succ&egrave;";
			}
		}
		
		return $this->redirect($this->generateUrl('web_profil_admin_afficher', array('message' => $message)));
    }
}
