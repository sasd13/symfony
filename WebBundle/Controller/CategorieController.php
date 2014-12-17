<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Categorie;
use MyWebsite\WebBundle\Entity\Contenu;

class CategorieController extends Controller
{	
	public function nouveauAction($idCv)
    {	
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();
		$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($idCv);
		$list_categories = $em->getRepository('MyWebsiteWebBundle:Categorie')->findByCv($cv->getId());
		$categorie = null;
		foreach($list_categories as $value)
		{
			if(strcmp($value->getIntitule(), $request->get('intitule')) == 0) $categorie = $value;
		}
			
		if ($categorie == null)
		{
			$categorie = new Categorie();
			$categorie->setIntitule($request->get('intitule'));
			$categorie->setTag($request->get('tag'));
			
			$categorie->setCv($cv);
			
			$em->persist($categorie);
			$em->flush();
			
			$list_contenus = null;
			$layout = 'cv-categorie-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $cv,
																					'categorie' => $categorie,
																					'list_contenus' => $list_contenus
			));
		}
		else
		{
			$layout = 'cv-categorie-new';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $cv
			));		
		}
    }
	
	public function modifierAction($idCv, $idCategorie)
    {
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();		
		$categorie = $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
			
		if ($categorie != null)
		{
			$categorie->setIntitule($request->get('intitule'));
			$categorie->setTag($request->get('tag'));
			
			$em->persist($categorie);
			$em->flush();
			
			$cv = $categorie->getCv();
			$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
			$layout = 'cv-categorie-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $cv,
																					'categorie' => $categorie,
																					'list_contenus' => $list_contenus
			));
		}
		else 
		{
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
	
	public function supprimerAction($idCv, $idCategorie)
    {		
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();		
		$categorie = $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
		
		if ($categorie != null)
		{
			$em->remove($categorie);
			$em->flush();
		}
		
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