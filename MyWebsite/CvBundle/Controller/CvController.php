<?php

namespace MyWebsite\CvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\CvBundle\Entity\Cv;

class CvController extends Controller
{
	public function listAction()
	{
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idMembre = $session->get('idMembre');
		
		if ($idMembre != null)
		{			
			$membre = $em->getRepository('MyWebsiteCvBundle:Membre')->find($idMembre);
			
			$list_cvs = $em->getRepository('MyWebsiteCvBundle:Cv')->findByProfil($membre->getProfil()->getId());
			
			return $this->render('MyWebsiteCvBundle:Web:cvs-list.html.twig', array(
																					'list_cvs' => $list_cvs
			));
		}
		else
		{
			$temp_list_cvs = $em->getRepository('MyWebsiteCvBundle:Cv')->findAll();
			$list_cvs = array();
			foreach($temp_list_cvs as $i => $value)
			{
				if($value->getActif() == true) $list_cvs[$i] = $value;
			}
			
			return $this->render('MyWebsiteCvBundle:Web:cvs-list.html.twig', array(
																							'list_cvs' => $list_cvs
			));
		}
	}
	
	public function loadAction($idCv)
	{
		$em = $this->getDoctrine()->getManager();
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->find($idCv);		
        $list_categories = $em->getRepository('MyWebsiteCvBundle:Category')->findByCv($cv->getId());
		$tab_list_contenus = array();
		foreach($list_categories as $i => $categorie)
		{
			$tab_list_contenus[$i] = array($categorie->getTag() => $em->getRepository('MyWebsiteCvBundle:Content')->findByCategory($categorie->getId()));
		}
		
		return $this->render('MyWebsiteCvBundle:Web:cv.html.twig', array(
																				'cv' => $cv,
																				'list_categories' => $list_categories,
																				'tab_list_contenus' => $tab_list_contenus
		));
	}
	
	public function profileListAction()
	{
	
	}
	
	public function profileNewAction()
	{
	
	}
	
	public function profileEditAction($idCv)
	{
	
	}
	
	public function profileDeleteAction($idCv)
	{
	
	}
	
	public function profileModelListAction()
	{
	
	}
	
	public function profileModelNewAction()
	{
	
	}
	
	public function profileModelEditAction($idModel)
	{
	
	}
	
	public function profileModelDeleteAction($idModel)
	{
	
	}
	
	public function profileModelCategoryNewAction()
	{
	
	}
	
	public function profileModelCategoryEditAction($idCategory)
	{
	
	}
	
	public function profileModelCategoryDeleteAction($idCategory)
	{
	
	}
	
	public function profileModelContentNewAction()
	{
	
	}
	
	public function profileModelContentEditAction($idContent)
	{
	
	}
	
	public function profileModelContentDeleteAction($idContent)
	{
	
	}
}