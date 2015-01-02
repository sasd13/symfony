<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Project;

class ProjectController extends Controller
{
	public function afficherAction($idProject)
    {		
		$em = $this->getDoctrine()->getManager();
		
		$project = $em->getRepository('MyWebsiteWebBundle:Project')->find($idProject);
		
        return $this->render('MyWebsiteWebBundle:Web:Project/project.html.twig', array(
																						'project' => $project
		));
    }
	
	public function afficherListProjectsAction()
    {
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
			
		$idMembre = $session->get('idMembre');
		
		if ($idMembre != null)
		{			
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);	
			
			$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($membre->getProfil()->getId());
			
			return $this->render('MyWebsiteWebBundle:Web:projects-list.html.twig', array(
																							'list_projects' => $list_projects
			));
		}
		else
		{
			$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->myFindAll();
			
			return $this->render('MyWebsiteWebBundle:Web:Public/projects-list.html.twig', array(
																									'list_projects' => $list_projects
			));
		}
    }
	
	public function nouveauAction()
    {		
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		//-- A modifier --//
		$project = null;
		
		$idMembre = $session->get('idMembre');
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->find($idMembre);
		
		$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($membre->getProfil()->getId());
		foreach($list_projects as $value)
		{
			if ((strcmp($value->getIntitule(), $request->request->get('intitule')) == 0) AND (strcmp($value->getPeriode(), $request->request->get('periode')) == 0))
			{
				$project = $value;
			}
		}
		//-- Fin --//
		
		if ($project == null)
		{
			$project = new Project();
			$project->setIntitule($request->request->get('intitule'));
			$project->setPeriode($request->request->get('periode'));
			$project->setTechnologie($request->request->get('technologie'));
			$project->setWebsite($request->request->get('website'));
			$project->setDescription($request->request->get('description'));
			
			$project->setProfil($membre->getProfil());
			
			$em->persist($project);
			$em->flush();
		}
		
		$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($membre->getProfil()->getId());
		$layout = 'project-list';
		
        return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'list_projects' => $list_projects
		));
    }
	
	public function modifierAction()
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();		
		
		$project = $em->getRepository('MyWebsiteWebBundle:Project')->find($request->request->get('idProject'));
		
		if($project != null)
		{
			$project->setIntitule($request->request->get('intitule'));
			$project->setPeriode($request->request->get('periode'));
			$project->setTechnologie($request->request->get('technologie'));
			$project->setWebsite($request->request->get('website'));
			$project->setDescription($request->request->get('description'));
			
			$em->persist($project);
			$em->flush();
		
			$layout = 'project-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'project' => $project
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_profil_project_list'));
    }
	
	public function supprimerAction($idProject)
    {		
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$project = $em->getRepository('MyWebsiteWebBundle:Project')->find($idProject);
		
		if($project != null)
		{
			$idProfil = $project->getProfil()->getId();
			
			$em->remove($project);
			$em->flush();
			
			$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($idProfil);
			$layout = 'project-list';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'list_projects' => $list_projects
			));	
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_profil_project_list'));
    }
}
