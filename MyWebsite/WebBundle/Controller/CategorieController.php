<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Categorie;
use MyWebsite\WebBundle\Entity\Contenu;

class CategorieController extends Controller
{	
	public function nouveauAction()
    {	
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$cv = $em->getRepository('MyWebsiteWebBundle:Cv')->find($request->request->get('idCv'));
		
		//-- A modifier --//
		$categorie = null;
		$list_contenus = null;
		
		$list_categories = $em->getRepository('MyWebsiteWebBundle:Categorie')->findByCv($cv->getId());
		foreach($list_categories as $value)
		{
			if(strcmp($value->getIntitule(), $request->request->get('intitule')) == 0) $categorie = $value;
		}
		//-- Fin --//
			
		if ($categorie == null)
		{
			$categorie = new Categorie();
			$categorie->setIntitule($request->request->get('intitule'));
			$categorie->setTag($request->request->get('tag'));
			
			$categorie->setCv($cv);
			
			$em->persist($categorie);
			$em->flush();
		}
		else
		{
			$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		}
		
		$layout = 'cv-categorie-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $cv,
																				'categorie' => $categorie,
																				'list_contenus' => $list_contenus
		));
    }
	
	public function modifierAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$categorie = $em->getRepository('MyWebsiteWebBundle:Categorie')->find($request->request->get('idCategorie'));
		
		if($categorie != null)
		{
			$categorie->setIntitule($request->request->get('intitule'));
			$categorie->setTag($request->request->get('tag'));
			
			$em->persist($categorie);
			$em->flush();
			
			$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
			$layout = 'cv-categorie-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $categorie->getCv(),
																					'categorie' => $categorie,
																					'list_contenus' => $list_contenus
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_profil_cv_list'));
    }
	
	public function supprimerAction($idCategorie)
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$categorie = $em->getRepository('MyWebsiteWebBundle:Categorie')->find($idCategorie);
		
		if($categorie != null)
		{
			$cv = $categorie->getCv();
			
			$em->remove($categorie);
			$em->flush();
			
			$list_categories = $em->getRepository('MyWebsiteWebBundle:Categorie')->findByCv($cv->getId());
			$layout = 'cv-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $cv,
																					'list_categories' => $list_categories
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_profil_cv_list'));
    }
}