<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use MyWebsite\WebBundle\Entity\Membre;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\Pays;

class ProfilController extends Controller
{	
	public function afficherAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
		if ($request->getMethod() == 'POST')
		{
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array(
																						'login' => $request->request->get('login'),
																						'password' => $request->request->get('password')
			));
		
			if ($membre == null) return $this->redirect($this->generateUrl('mywebsiteweb_login'));
			else
			{
				$session->set('idMembre', $membre->getId());
				
				$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
				$layout = 'profil-edit';
				
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout,
																						'membre' => $membre,
																						'list_pays' => $list_pays
				));
			}
		}	
		else 
		{
			$idMembre = $session->get('idMembre');
	
			if ($idMembre == null) return $this->redirect($this->generateUrl('mywebsiteweb_login'));
			else 
			{
				$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
				$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
				$layout = 'profil-edit';				
				
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout,
																						'membre' => $membre,
																						'list_pays' => $list_pays
				));
			}	
		}
	}
	
	public function nouveauAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array(
																					'login' => $request->request->get('login')
		));
				
		if ($membre == null AND (strcmp($request->request->get('password'), $request->request->get('confirmpassword')) == 0))
		{
			$profil = new Profil();
			$profil->setNom($request->request->get('nom'));
			$profil->setPrenom($request->request->get('prenom'));
			$profil->setEmail($request->request->get('email'));
			$profil->setTelephone($request->request->get('telephone'));
			$profil->setVille($request->request->get('ville'));
			$profil->setPays($request->request->get('pays'));
			
			$membre = new Membre();
			$membre->setLogin($request->request->get('login'));
			$membre->setPassword($request->request->get('password'));
			
			$membre->setProfil($profil);
			
			$em->persist($profil);
			$em->persist($membre);
			$em->flush();
				
			$session = $this->getRequest()->getSession();
			$session->set('idMembre', $membre->getId());
			
			$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
			$layout = 'profil-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'membre' => $membre,
																					'list_pays' => $list_pays
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_register'));
    }
	
	public function modifierAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idMembre = $session->get('idMembre');		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		if($membre != null)
		{
			$membre->getProfil()->setNom($request->request->get('nom'));
			$membre->getProfil()->setPrenom($request->request->get('prenom'));
			$membre->getProfil()->setEmail($request->request->get('email'));
			$membre->getProfil()->setTelephone($request->request->get('telephone'));
			$membre->getProfil()->setVille($request->request->get('ville'));
			$membre->getProfil()->setPays($request->request->get('pays'));
			
			$em->persist($membre);
			$em->flush();
			
			$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
			$layout = 'profil-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'membre' => $membre,
																					'list_pays' => $list_pays
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_logout'));
    }
	
	public function supprimerAction($idMembre)
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		if($membre != null)
		{
			$em->remove($membre->getProfil());
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl('mywebsiteweb_logout'));
    }
	
	public function afficherMdpAction()
    {
		$layout = 'profil-mdp-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout
		));
	}
	
	public function modifierMdpAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();		
		
		$idMembre = $session->get('idMembre');	
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		if (($membre->get('password') == $request->request->get('password')) AND ($request->request->get('newpassword') == $request->request->get('confirmnewpassword')))
		{
			$membre->setPassword($request->request->get('password'));
			$em->persist($membre);
			$em->flush();
		}
				
		$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
		$layout = 'profil-mdp-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout
		));
    }
	
	public function listeProjectAction()
    {
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
		$idMembre = $session->get('idMembre');		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($membre->getProfil()->getId());
		$layout = 'project-list';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'list_projects' => $list_projects,
		));
	}
	
	public function gestionProjectAction($idProject)
    {		
		if ($idProject == 0) 
		{
			$layout = 'project-new';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout
			));
		}
		else 
		{
			$em = $this->getDoctrine()->getManager();
			
			$project = $em->getRepository('MyWebsiteWebBundle:Project')->find($idProject);
			$layout = 'project-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'project' => $project
			));
		}
	}
	
	public function listeCvAction()
    {
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idMembre = $session->get('idMembre');		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		$list_cvs = $em->getRepository('MyWebsiteWebBundle:Cv')->findByProfil($membre->getProfil()->getId());
		$layout = 'cv-list';
		
        return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'list_cvs' => $list_cvs
		));
	}
	
	public function gestionCvAction($idCv)
    {
		if ($idCv == 0) 
		{
			$layout = 'cv-new';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout
			));
		}
		else
		{
			$em = $this->getDoctrine()->getManager();
			
			$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($idCv);
			$list_categories = $em->getRepository('MyWebsiteWebBundle:Categorie')->findByCv($cv->getId());
			$layout = 'cv-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $cv,
																					'list_categories' => $list_categories
			));
		}
	}
	
	public function gestionCategorieAction($idCategorie)
    {
		$em = $this->getDoctrine()->getManager();
		
		$categorie = $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
		$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		$layout = 'cv-categorie-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $categorie->getCv(),
																				'categorie' => $categorie,
																				'list_contenus' => $list_contenus
		));
	}
	
	public function listePortfolioAction()
    {
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idMembre = $session->get('idMembre');		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		$list_albums = $em->getRepository('MyWebsiteWebBundle:Album')->findByProfil($membre->getProfil()->getId());
		$layout = 'portfolio-edit';
		
        return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'list_albums' => $list_albums
		));
	}
	
	public function gestionPortfolioAction($idPortfolio)
    {
		$layout = 'portfolio-new';
		
        return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout
		));
	}
}
