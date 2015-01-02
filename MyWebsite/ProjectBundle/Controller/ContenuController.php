<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Categorie;
use MyWebsite\WebBundle\Entity\Contenu;

class ContenuController extends Controller
{
	public function nouveauAction()
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$categorie =  $em->getRepository('MyWebsiteWebBundle:Categorie')->find($request->request->get('idCategorie'));
		
		//-- A modifier --//
		$contenu = null;
		$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		foreach($list_contenus as $value)
		{
			if ((strcmp($value->getTitre(), $request->request->get('titre')) == 0) AND (strcmp($value->getDescription1(), $request->request->get('description1')) == 0))
			{
				$contenu = $value;
			}
		}
		//-- Fin --//
		
		if ($contenu == null)
		{			
			$contenu = new Contenu();
			$contenu->setTitre($request->request->get('titre'));
			$contenu->setDescription1($request->request->get('description1'));
			$contenu->setDescription2($request->request->get('description2'));
			$contenu->setDescription3($request->request->get('description3'));
			
			$contenu->setCategorie($categorie);
			
			$em->persist($contenu);
			$em->flush();
		}
		
		$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($categorie->getId());
		$layout = 'cv-categorie-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'cv' => $categorie->getCv(),
																				'categorie' => $categorie,
																				'list_contenus' => $list_contenus
		));
    }
	
	public function modifierAction()
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$contenu = $em->getRepository('MyWebsiteWebBundle:Contenu')->find($request->request->get('idContenu'));
		
		if($contenu != null)
		{
			$contenu->setTitre($request->request->get('titre'));
			$contenu->setDescription1($request->request->get('description1'));
			$contenu->setDescription2($request->request->get('description2'));
			$contenu->setDescription3($request->request->get('description3'));
			
			$em->persist($contenu);
			$em->flush();
			
			$list_contenus = $em->getRepository('MyWebsiteWebBundle:Contenu')->findByCategorie($contenu->getCategorie()->getId());
			$layout = 'cv-categorie-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'cv' => $contenu->getCategorie()->getCv(),
																					'categorie' => $contenu->getCategorie(),
																					'list_contenus' => $list_contenus
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_profil_cv_list'));
    }
	
	public function supprimerAction($idContenu)
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$contenu = $em->getRepository('MyWebsiteWebBundle:Contenu')->find($idContenu);
		
		if($contenu != null)
		{
			$categorie = $contenu->getCategorie();
			
			$em->remove($contenu);
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
}