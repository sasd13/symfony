<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Cv;
use MyWebsite\WebBundle\Entity\Categorie;
use MyWebsite\WebBundle\Entity\Contenu;

class CvController extends Controller
{
	public function afficherAction($idCv)
    {		
		$em = $this->getDoctrine()->getManager();
		
		$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($idCv);		
        $list_categories = $em->getRepository('MyWebsiteWebBundle:Categorie')->findByCv($cv->getId());
		$tab_list_contenus = array();
		foreach($list_categories as $i => $categorie)
		{
			$tab_list_contenus[$i] = array($categorie->getTag() => $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId()));
		}
		
		return $this->render('MyWebsiteWebBundle:Web:Cv/cv.html.twig', array(
																				'cv' => $cv,
																				'list_categories' => $list_categories,
																				'tab_list_contenus' => $tab_list_contenus
		));
    }
	
	public function afficherListCvsAction()
    {
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idMembre = $session->get('idMembre');
		
		if ($idMembre != null)
		{			
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
			
			$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($membre->getProfil()->getId());
			
			return $this->render('MyWebsiteWebBundle:Web:cvs-list.html.twig', array(
																					'list_cvs' => $list_cvs
			));
		}
		else
		{
			$temp_list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->myFindAll();
			$list_cvs = array();
			foreach($temp_list_cvs as $i => $value)
			{
				if($value->getActif() == true) $list_cvs[$i] = $value;
			}
			
			return $this->render('MyWebsiteWebBundle:Web:Public/cvs-list.html.twig', array(
																							'list_cvs' => $list_cvs
			));
		}
    }
	
	public function nouveauAction()
    {		
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		//-- A modifier --//
		$cv = null;
		$list_categories = null;
		
		$idMembre = $session->get('idMembre');		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);	
		
		$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($membre->getProfil()->getId());
		$cv = null;
		foreach($list_cvs as $value)
		{
			if ((strcmp($value->getIntitule(), $request->request->get('intitule')) == 0) AND (strcmp($value->getDisponibilite(), $request->request->get('disponibilite')) == 0))
			{
				$cv = $value;
			}
		}
		//-- Fin --//
			
		if ($cv == null)
		{
			$cv = new Cv();
			$cv->setIntitule($request->request->get('intitule'));
			$cv->setDescription($request->request->get('description'));
			$cv->setDisponibilite($request->request->get('disponibilite'));
			$cv->setMobilite($request->request->get('mobilite'));
			$cv->setActif(false);
			
			$cv->setProfil($membre->getProfil());
		
			$em->persist($cv);
			$em->flush();
		}
		else 
		{
			$list_categories = $em->getRepository('MyWebsiteWebBundle:Categorie')->findByCv($cv->getId());
		}

		$layout = 'cv-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $cv,
																				'list_categories' => $list_categories
		));
    }
	
	public function modifierAction($idCv, $active)
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();		
		
		if ($request->getMethod() == 'POST') 
		{
			$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($request->request->get('idCv'));
			
			if($cv != null)
			{
				$cv->setIntitule($request->request->get('intitule'));
				$cv->setDescription($request->request->get('description'));
				$cv->setDisponibilite($request->request->get('disponibilite'));
				$cv->setMobilite($request->request->get('mobilite'));
			
				$em->persist($cv);
				$em->flush();
			
				$layout = 'cv-edit';
			
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout,
																						'cv' => $cv
				));
			}
			else return $this->redirect($this->generateUrl('mywebsiteweb_profil_cv_list'));
		}	
		else 
		{
			$session = $this->getRequest()->getSession();
			
			$idMembre = $session->get('idMembre');		
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
			
			$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($membre->getProfil()->getId());
			foreach($list_cvs as $cv)
			{
				if ($cv->getId() == $idCv) $cv->setActif(true);
				else $cv->setActif(false);
				
				$em->persist($cv);
			}
			$em->flush();
			
			$layout = 'cv-list';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'list_cvs' => $list_cvs
			));	
		}
    }
	
	public function supprimerAction($idCv)
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($idCv);
		
		if($cv != null)
		{
			$idProfil = $cv->getProfil()->getId();
			
			$em->remove($cv);
			$em->flush();
			
			$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($idProfil);
			$layout = 'cv-list';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'list_cvs' => $list_cvs
			));	
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_profil_cv_list'));
    }
}