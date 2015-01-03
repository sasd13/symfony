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
		
		$profil = null;
		if ($session->get('idProfil') == null)
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
			if ($request->getMethod() !== 'POST' OR strcmp($request->request->get('password'), $admin->getPassword()) !== 0)
			{
				return $this->render('MyWebsiteWebBundle:Profil:login.html.twig');
			}
			
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
			$session->set('idProfil', $profil->getId());
		}
		else 
		{
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($session->get('idProfil'));
		}
		
		$formProfil = $this->createFormBuilder($profil)
			->add('firstName')
			->add('lastName')
			->getForm();
		
		$layout = 'profil-edit';				
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																				'layout' => $layout, 
																				'profil' => $profil, 
																				'formProfil' => $formProfil->createView()
		));
	}
	
	public function modifierAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		if($session->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->getForm();
		
		$message = "Les informations n'ont pas été modifiées";
		$formPicture->handleRequest($request);
		if($session->get('idProfil') != null AND $formPicture->isValid())
		{
			$em->persist($document);
			$em->flush();
		
			$message = "Les informations ont été modifiées avec succès";
		}
		
		$layout = 'profil-edit';
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($session->get('idProfil'));
		
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil, 
																					'formPicture' => $formPicture->createView(),
																					'message' => $message
		));
    }
	
	public function afficherPictureAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
		if($session->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->add('file')
			->getForm();
		
		$layout = 'profil-edit';
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($session->get('idProfil'));
		
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil, 
																					'formPicture' => $formPicture->createView()
		));
	}
	
	public function modifierPictureAction($displayPicture)
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		if($session->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->getForm();
		
		$message = "Les informations n'ont pas été modifiées";
		$formPicture->handleRequest($request);
		if($session->get('idProfil') != null AND $formPicture->isValid())
		{
			$em->persist($document);
			$em->flush();
		
			$message = "Les informations ont été modifiées avec succès";
		}
		
		$layout = 'profil-edit';
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($session->get('idProfil'));
		
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil, 
																					'formPicture' => $formPicture->createView(),
																					'message' => $message
		));
    }
	
	/**
	 * @Template()
	 */
	public function uploadAction()
	{
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		if($session->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
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
		
		$layout = 'profil-edit';
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($session->get('idProfil'));	
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout, 
																				'profil' => $profil, 
																				'form' => $formPicture->createView()
		));
	}
	
	public function logoutAction()
    {
		$session = $this->getRequest()->getSession();
		
		if ($session->get('idProfil') != null) $session->remove('idProfil');
        return $this->redirect($this->generateUrl('web_home'));
    }
}
