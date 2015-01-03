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
		$em = $this->getDoctrine()->getManager();
		
		$profil = null;
		if ($request->getSession()->get('idProfil') == null)
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
			if ($request->getMethod() !== 'POST' OR strcmp($request->request->get('password'), $admin->getPassword()) !== 0)
			{
				return $this->render('MyWebsiteWebBundle:Profil:login.html.twig');
			}
			
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
			$request->getSession()->set('idProfil', $profil->getId());
		}
		else 
		{
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($request->getSession()->get('idProfil'));
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
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($request->getSession()->get('idProfil'));
		$formProfil = $this->createFormBuilder($profil)
			->add('firstName')
			->add('lastName')
			->getForm();
		
		$message = "Les informations n'ont pas été modifiées";
		$formProfil->handleRequest($request);
		if($request->getSession()->get('idProfil') != null AND $formProfil->isValid())
		{
			$em->persist($profil);
			$em->flush();
		
			$message = "Les informations ont été modifiées avec succès";
		}
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($request->getSession()->get('idProfil'));
		$layout = 'profil-edit';		
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil, 
																					'formProfil' => $formProfil->createView(),
																					'message' => $message
		));
    }
	
	public function afficherPictureAction()
    {
		$request = $this->getRequest();	
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$document = new Document();
		$formPicture = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->add('file')
			->getForm();
		
		$layout = 'profil-picture-edit';
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout,
																					'formPicture' => $formPicture->createView()
		));
	}
	
	public function modifierPictureAction($displayPicture)
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfil') == null)
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
		if($request->getSession()->get('idProfil') != null AND $formPicture->isValid())
		{
			$em->persist($document);
			$em->flush();
		
			$message = "Les informations ont été modifiées avec succès";
		}
		
		$layout = 'profil-picture-edit';
		return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout,
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
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfil') == null)
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
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout, 
																				'form' => $formPicture->createView()
		));
	}
	
	public function logoutAction()
    {
		if ($request->getSession()->get('idProfil') != null) $request->getSession()->remove('idProfil');
		
        return $this->redirect($this->generateUrl('web_home'));
    }
}
