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
		
		if ($request->getMethod() == 'POST')
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Admin')->findOneBy(array('login' => $request->request->get('login'), 'password' => $request->request->get('password')));
			if ($admin == null) return $this->redirect($this->generateUrl('web_login'));
			else
			{
				$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
				$session->set('idProfil', $profil->getId());
				
				$document = new Document();
				$form = $this->createFormBuilder($document)
					->add('name')
					->add('file')
					->getForm();
				
				$layout = 'profil-edit';				
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout, 
																						'profil' => $profil, 
																						'form' => $formPicture->createView()
				));
			}
		}
		else 
		{
			$idProfil = $session->get('idProfil');
	
			if ($idProfil == null) return $this->redirect($this->generateUrl('mywebsiteweb_login'));
			else 
			{
				$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($idProfil);
				
				$document = new Document();
				$form = $this->createFormBuilder($document)
					->add('name')
					->add('file')
					->getForm();
				
				$layout = 'profil-edit';				
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout, 
																						'profil' => $profil, 
																						'form' => $formPicture->createView()
				));
			}	
		}
	}
	
	public function modifierAction($displayPicture)
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idProfil = $session->get('idProfil');		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($idProfil);
		
		if($profil != null)
		{
			$profil->setDisplayPicture($displaPicture);
			
			$em->persist($profil);
			$em->flush();
			
			
			$layout = 'profil-edit';			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'profil' => $profil
			));
		}
		else return $this->redirect($this->generateUrl('web_logout'));
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

				$this->redirect($this->generateUrl(...));
			}
		}

		return array('form' => $form->createView());
	}
	
	public function modifierAdminAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();		
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
		
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
