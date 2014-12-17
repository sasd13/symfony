<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Categorie;
use MyWebsite\WebBundle\Entity\Contenu;

class ContenuController extends Controller
{
	public function nouveauAction($idCv, $idCategorie)
    {		
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();		
		$categorie =  $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
		$cv = $categorie->getCv();
		
		$contenu = $em->getRepository('MyWebsiteWebBundle:Contenu')->findOneBy(array(
																						'titre' => $request->get('titre'),
																						'description1' => $request->get('description1')
		));
		
		if ($contenu == null)
		{			
			$contenu = new Contenu();
			$contenu->setTitre($request->get('titre'));
			$contenu->setDescription1($request->get('description1'));
			$contenu->setDescription2($request->get('description2'));
			$contenu->setDescription3($request->get('description3'));
			
			$contenu->setCategorie($categorie);
			
			$em->persist($contenu);
			$em->flush();
		}
		
		$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		$layout = 'cv-categorie-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $cv,
																				'categorie' => $categorie,
																				'list_contenus' => $list_contenus
		));
    }
	
	public function modifierAction($idCv, $idCategorie, $idContenu)
    {		
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();
		$categorie =  $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
		$cv = $categorie->getCv();
		$contenu = $em->getRepository('MyWebsiteWebBundle:Contenu')->find($idContenu);
		
		if ($contenu != null)
		{			
			$contenu->setTitre($request->get('titre'));
			$contenu->setDescription1($request->get('description1'));
			$contenu->setDescription2($request->get('description2'));
			$contenu->setDescription3($request->get('description3'));
			
			$em->persist($contenu);
			$em->flush();
		}
		
		$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		$layout = 'cv-categorie-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $cv,
																				'categorie' => $categorie,
																				'list_contenus' => $list_contenus
		));
    }
	
	public function supprimerAction($idCv, $idCategorie, $idContenu)
    {		
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();
		$categorie =  $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
		$cv = $categorie->getCv();
		$contenu = $em->getRepository('MyWebsiteWebBundle:Contenu')->find($idContenu);
		
		if ($contenu != null)
		{
			$em->remove($contenu);
			$em->flush();
		}
		
		$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		$layout = 'cv-categorie-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $cv,
																				'categorie' => $categorie,
																				'list_contenus' => $list_contenus
		));
    }
}