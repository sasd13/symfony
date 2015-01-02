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
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
		$profil->setDisplayPicture($displaPicture);
		
		$em->persist($profil);
		$em->flush();
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->getForm();
		
		$layout = 'profil-edit';				
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout, 
																				'profil' => $profil, 
																				'formPicture' => $formPicture->createView()
		));
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
			$form->handleRequest($this->getRequest());
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
			
				$em->persist($document);
				$em->flush();

				$this->redirect($this->generateUrl('web_profil'));
			}
		}

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
			
		if($request->getMethod() == 'POST')
		{
			$formAdmin->bind($request);
			if($formAdmin->isValid())
			{
				$em->persist($admin);
				$em->flush();
				
				$formAdmin = $this->createFormBuilder($admin)
					->add('emailBackup', 'email')
					->add('password', 'password')
					->getForm();
			}
		}
		
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
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Administratoristrator')->find(1);
		$admin->setPassword($request->request->get('newpassword'));
		
		if ($request->hasParameter('password') AND ($admin->get('password') == $request->request->get('password')) AND ($request->request->get('newpassword') == $request->request->get('confirmnewpassword')))
		{
			$admin->setPassword($request->request->get('newpassword'));
			$em->persist($profil);
			$em->flush();
		}
				
		$layout = 'profil-admin-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array('layout' => $layout));
    }
}
