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
		$em = $this->getDoctrine()->getManager();
		
		$session = $this->getRequest()->getSession();		
		$login = $session->get('login');
		
		if ($login != null)
		{			
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $login));
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->findOneByMembre($membre->getId());
			
			$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($profil->getId());
			
			return $this->render('MyWebsiteWebBundle:Web:cvs-list.html.twig', array('list_cvs' => $list_cvs));
		}
		else
		{
			$temp_list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->myFindAll();
			$list_cvs = array();
			foreach($temp_list_cvs as $i => $value)
			{
				if($value->getActif() == true) $list_cvs[$i] = $value;
			}
			
			return $this->render('MyWebsiteWebBundle:Web:Public/cvs-list.html.twig', array('list_cvs' => $list_cvs));
		}
    }
	
	public function nouveauAction()
    {		
		$request = $this->getRequest();
		
		$session = $this->getRequest()->getSession();
		$login = $session->get('login');
		
		$em = $this->getDoctrine()->getManager();
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $login));
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->findOneByMembre($membre->getId());
		
		$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($profil->getId());
		$cv = null;
		foreach($list_cvs as $value)
		{
			if ((strcmp($value->getIntitule(), $request->get('intitule')) == 0) AND (strcmp($value->getDisponibilite(), $request->get('disponibilite')) == 0))
			{
				$cv = $value;
			}
		}
			
		if ($cv == null)
		{
			$cv = new Cv();
			$cv->setIntitule($request->get('intitule'));
			$cv->setDescription($request->get('description'));
			$cv->setDisponibilite($request->get('disponibilite'));
			$cv->setMobilite($request->get('mobilite'));
			$cv->setActif(false);
			
			$cv->setProfil($profil);
		
			$em->persist($cv);
			$em->flush();
		}
		
		$list_categories = null;
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
			$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($idCv);
			
			if ($cv == null) $this->redirect($this->generateUrl('mywebsiteweb_profil_cv_list'));
			else
			{
				$cv->setIntitule($request->get('intitule'));
				$cv->setDescription($request->get('description'));
				$cv->setDisponibilite($request->get('disponibilite'));
				$cv->setMobilite($request->get('mobilite'));
			
				$em->persist($cv);
				$em->flush();
				
				$layout = 'cv-edit';
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout,
																						'cv' => $cv
				));
			}
		}	
		else 
		{
			$session = $this->getRequest()->getSession();
			$login = $session->get('login');
			
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $login));
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->findOneByMembre($membre->getId());
			
			$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($profil->getId());
			foreach($list_cvs as $cv)
			{
				if ($cv->getId() == $idCv) $cv->setActif(true);
				else $cv->setActif(false);
				
				$em->persist($cv);
				$em->flush();
			}
			
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
		
		if ($cv != null)
		{
			$em->remove($cv);
			$em->flush();
		}
		
		$session = $this->getRequest()->getSession();
		$login = $session->get('login');
			
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $login));
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->findOneByMembre($membre->getId());
		
		$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($profil->getId());
		
		$layout = 'cv-list';
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'list_cvs' => $list_cvs
		));	
    }
}