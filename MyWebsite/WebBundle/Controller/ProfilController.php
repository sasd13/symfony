<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProfilController extends Controller
{
	public function afficherAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$profil = null;
		if($request->getSession()->get('idProfil') == null)
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
		
		if($profil != null)
		{
			$formProfil = $this->createFormBuilder($profil)
				->add('firstName', 'text', array('required' => false))
				->add('lastName', 'text', array('required' => false))
				->getForm();
		
			$category = new Category("Coordonnées", "coordonnees");
			$category->setEditManager($profil->getEditManager());
		
			$formCategory = $this->createFormBuilder($category)
				->add('title', 'text', array('required' => false))
				->add('tag', 'text', array('required' => false))
				->getForm();
		
			$layout = 'profil-edit';			
			return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																					'layout' => $layout, 
																					'profil' => $profil,
																					'category' => $category,
																					'formProfil' => $formProfil->createView(),
																					'formCategory' => $formCategory->createView()
			));
		}
		
		return $this->redirect($this->generateUrl('web_profil_error'));
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
		if($profil != null)
		{
			$formProfil = $this->createFormBuilder($profil)
				->add('firstName', 'text')
				->add('lastName', 'text')
				->getForm();
			$formProfil->handleRequest($request);
			
			$category = $em->getRepository('MyWebsiteWebBundle:Category')->find($request->request->get('idCategory'));
			if($category != null)
			{
				$formCategory = $this->createFormBuilder($category)
					->add('title', 'text')
					->add('tag', 'text')
					->getForm();
				$formCategory->handleRequest($request);
				
				$message = "Les informations n'ont pas été enregistrées";
				if($request->getSession()->get('idProfil') != null)
				{
					$em->persist($profil);
					$em->persist($category);
					$em->flush();
					
					$message = "Les informations ont été enregistrées avec succès";
				}
		
				$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($request->getSession()->get('idProfil'));
				$layout = 'profil-edit';		
				return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																							'layout' => $layout, 
																							'profil' => $profil, 
																							'category' => $category,
																							'formProfil' => $formProfil->createView(),
																							'formCategory' => $formCategory->createView()
																							'message' => $message
				));
			}
		}
		
		return $this->redirect($this->generateUrl('web_profil_error'));
    }
	
	public function afficherPictureAction()
    {
		$request = $this->getRequest();	
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfil') == null)
		{
			return $this->redirect($this->generateUrl('web_profil_afficher'));
		}
		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($request->getSession()->get('idProfil'));
		if($profil != null)
		{
			$category = $em->getRepository('MyWebsiteWebBundle:Category')->findOneByTag('profil_picture');
			if($category != null)
			{
				$documents = $category->getDocuments();
				$picture = $document[0];
				$formPicture = $this->createFormBuilder($picture)
					->add('name')
					->add('file')
					->getForm();
		
				$layout = 'profil-picture-edit';
				return $this->render('MyWebsiteWebBundle:Profil:profil.html.twig', array(
																							'layout' => $layout,
																							'picture' => $picture,
																							'formPicture' => $formPicture->createView()
				));
			}
		}
		
		return $this->redirect($this->generateUrl('web_profil_error'));
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
		$formPicture->handleRequest($request);
		
		$message = null;
		if($request->getSession()->get('idProfil') != null)
		{
			$em->persist($document);
			$em->flush();
		}
		else
		{
			$message = "Les informations n'ont pas été modifiées";
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
		if ($this->getRequest()->getSession()->get('idProfil') != null) $this->getRequest()->getSession()->remove('idProfil');
		
        return $this->redirect($this->generateUrl('web_home'));
    }
}
