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
		
        return $this->render('MyWebsiteWebBundle:Web:Project/project.html.twig', array('project' => $project));
    }
	
	public function afficherListProjectsAction()
    {
		$em = $this->getDoctrine()->getManager();
		
		$session = $this->getRequest()->getSession();		
		$login = $session->get('login');
		
		if ($login != null)
		{			
			$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $login));
			$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->findOneByMembre($membre->getId());
			
			$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($profil->getId());
			
			return $this->render('MyWebsiteWebBundle:Web:projects-list.html.twig', array('list_projects' => $list_projects));
		}
		else
		{
			$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->myFindAll();
			
			return $this->render('MyWebsiteWebBundle:Web:Public/projects-list.html.twig', array('list_projects' => $list_projects));
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
		
		$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($profil->getId());
		$project = null;
		foreach($list_projects as $value)
		{
			if ((strcmp($value->getIntitule(), $request->get('intitule')) == 0) AND (strcmp($value->getPeriode(), $request->get('periode')) == 0))
			{
				$project = $value;
			}
		}
		
		if ($project == null)
		{
			$project = new Project();
			$project->setIntitule($request->get('intitule'));
			$project->setPeriode($request->get('periode'));
			$project->setTechnologie($request->get('technologie'));
			$project->setWebsite($request->get('website'));
			$project->setDescription($request->get('description'));
			
			$project->setProfil($profil);
			
			$em->persist($project);
			$em->flush();
		}
		
		$layout = 'project-edit';
        return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'project' => $project
		));
    }
	
	public function modifierAction($id)
    {		
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();		
		$project = $em->getRepository('MyWebsiteWebBundle:Project')->find($id);
		
		if ($project == null) $this->redirect($this->generateUrl('mywebsiteweb_profil_project_list'));
		else
		{
			$project->setIntitule($request->get('intitule'));
			$project->setPeriode($request->get('periode'));
			$project->setTechnologie($request->get('technologie'));
			$project->setWebsite($request->get('website'));
			$project->setDescription($request->get('description'));
			
			$em->persist($project);
			$em->flush();
			
			$layout = 'project-edit';
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'project' => $project
			));
		}
    }
	
	public function supprimerAction($id)
    {		
		$request = $this->getRequest();
		
		$em = $this->getDoctrine()->getManager();		
		$project = $em->getRepository('MyWebsiteWebBundle:Project')->find($id);
		
		if ($project != null)
		{
			$em->remove($project);
			$em->flush();
		}
		
		$session = $this->getRequest()->getSession();		
		$login = $session->get('login');
		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $login));
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->findOneByMembre($membre->getId());
		
		$list_projects = $em->getRepository('MyWebsiteWebBundle:Project')->findByProfil($profil->getId());
		
		$layout = 'project-list';
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																				'layout' => $layout,
																				'list_projects' => $list_projects
		));	
    }
}
