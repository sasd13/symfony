<?php

namespace MyWebsite\CvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\CvBundle\Entity\Cv;
use MyWebsite\WebBundle\Entity\Category;

class CvController extends Controller
{
	public function listAction()
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$layouter = $this->container->get('cv_layouter');
		
		$client = ($request->getSession()->get('idUser') != null) 
			? $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'))
			: null
		;
		
		$cvs = ($client != null) 
			? $em->getRepository('MyWebsiteCvBundle:Cv')->findByClient($client)
			: $em->getRepository('MyWebsiteCvBundle:Cv')->findAll()
		;
		
		return $this->render($layouter::LAYOUT_CV_LIST, array(
			'cvs' => $cvs
		));
	}
	
	public function loadAction($idCv)
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$layouter = $this->container->get('cv_layouter');
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->find($idCv);
		
		if($cv == null)
		{
			$router = $this->container->get('cv_router');
			return $this->redirect($this->generateUrl($router::ROUTE_CV_LIST));
		}
		
		return $this->render($layouter::LAYOUT_CV_LOAD, array(
			'cv' => $cv
		));
	}
	
	public function profileListAction()
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$layouter = $this->container->get('cv_layouter');
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		$cvs = $em->getRepository('MyWebsiteCvBundle:Cv')->findByClient($client);
		
		return $this->render($layouter::LAYOUT_CV_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_CV_PROFILE_LIST,
			'user' => $client,
			'cvs' => $cvs
		));
	}
	
	public function profileNewAction()
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('cv_router');
		$layouter = $this->container->get('cv_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		$cv = new Cv();
		$form = $this->createForm('cv_cv', $cv, array(
			'action' => $this->generateUrl($router::ROUTE_CV_PROFILE_NEW)
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$cv->setClient($client);
				$cv = $this->container->get('cv_recorder')->createCv($cv);
				
				if($cv != null)
				{
					return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_CV_EDIT, array(
						'idCv' => $cv->getId()
					)));
				}				
			}
		}
		
		return $this->render($layouter::LAYOUT_CV_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_CV_PROFILE_NEW,
			'user' => $client,
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function profileEditAction($idCv)
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$router = $this->container->get('cv_router');
		$layouter = $this->container->get('cv_layouter');
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->find($idCv);
		if($cv == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
		}
		
		$formCv = $this->createForm('cv_cv', $cv, array(
			'action' => $this->generateUrl($router::ROUTE_CV_PROFILE_EDIT)
		));
		
		$category = new Category('content');
		
		$formCategory = $this->createForm('web_category', $category, array(
			'action' => $this->generateUrl($router::ROUTE_CV_PROFILE_CATEGORY_EDIT)
		));
		
		$messageCv = "* Denotes Required Field";
		$messageCategory = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$formCv->submit($request->get($formCv->getName()), false);
			
			$messageCv = "Les informations n'ont pas été enregistrées";
			
			if($formCv->isValid())
			{
			
			}
			
			$formCategory->submit($request->get($formCategory->getName()), false);
			
			$messageCategory = "Les informations n'ont pas été enregistrées";
			
			if($formCategory->isValid())
			{
			
			}
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		return $this->render($layouter::LAYOUT_CV_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_CV_PROFILE_EDIT,
			'user' => $client,
			'cv' => $cv,
			'formCv' => $formCv->createView(),
			'formCategory' => $formCategory->createView(),
			'messageCv' => $messageCv,
			'messageCategory' => $messageCategory
		));
	}
	
	public function profileActiveAction($idCv, $active)
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