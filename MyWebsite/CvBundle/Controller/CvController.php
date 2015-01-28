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
		
		//Services
		$layouter = $this->container->get('cv_layouter');
		//End services
		
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
		
		//Services
		$layouter = $this->container->get('cv_layouter');
		//End services
		
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
		
		//Services
		$layouter = $this->container->get('cv_layouter');
		//End services
		
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
		
		//Services
		$router = $this->container->get('cv_router');
		$layouter = $this->container->get('cv_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
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
					return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_EDIT, array(
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
		
		//Services
		$router = $this->container->get('cv_router');
		$layouter = $this->container->get('cv_layouter');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->myFindWithCategories($idCv);
		if($cv == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
		}
		
		$form = $this->createForm('cv_cv', $cv, array(
			'action' => $this->generateUrl($router::ROUTE_CV_PROFILE_EDIT, array(
				'idCv' => $cv->getId()
			))
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$cvOld = $this->container->get('cv_copy')->getCvCopy($cv);
			
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$updated = $this->container->get('cv_recorder')->updateCv($cv, $cvOld);
				
				if($updated === true)
				{
					$message = "Les informations ont été enregistrées avec succès";
				}
			}
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		
		return $this->render($layouter::LAYOUT_CV_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_CV_PROFILE_EDIT,
			'user' => $client,
			'cv' => $cv,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function profileActiveAction($idCv, $active)
	{
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('cv_router');
		//End services
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->myFindWithCategoryInfo($idCv);
		if($cv != null)
		{
			$cv->setActive($active);
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
	}
	
	public function profileDeleteAction($idCv)
	{
	
	}
	
	public function profileCategoryNewAction($idCv)
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('cv_router');
		$recorder = $this->container->get('cv_recorder');
		//End services
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->find($idCv);
		if($cv == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
		}
		
		$titleCategory = $request->request->get('new_category_title');
		
		$bufferCv = $em->getRepository('MyWebsiteCvBundle:Cv')->myFindByCategoryTitle($idCv, $titleCategory);
		if($bufferCv == null)
		{
			$category = $this->container->get('web_recorder')->createCategory(
				$cv,
				'content',
				$titleCategory,
				strtolower('cv_category_'.$titleCategory)
			);
			
			if($category != null)
			{
				return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_CATEGORY_EDIT, array(
					'idCategory' => $category->getId()
				)));
			}
		}
		
		return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
	}
	
	public function profileCategoryEditAction($idCategory)
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Services
		$router = $this->container->get('cv_router');
		$layouter = $this->container->get('cv_layouter');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$category = $em->getRepository('MyWebsiteWebBundle:Category')->myFindWithContents($idCategory);
		//die(var_dump($category->getContents()));
		if($category == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
		}
		
		$form = $this->createForm('web_category', $category, array(
			'action' => $this->generateUrl($router::ROUTE_CV_PROFILE_CATEGORY_EDIT, array(
				'idCategory' => $category->getId()
			))
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$categoryOld = $this->container->get('web_copy')->getCategoryCopy($category);
			
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$updated = $this->container->get('web_recorder')->updateCategory($category, $categoryOld);
				
				if($updated === true)
				{
					$message = "Les informations ont été enregistrées avec succès";
				}
			}
		}
		
		$client = $em->getRepository('MyWebsiteProfileBundle:Client')->find($request->getSession()->get('idUser'));
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->find($category->getModuleEntity()->getId());
		
		return $this->render($layouter::LAYOUT_CV_PROFILE, array(
			'subLayout' => $layouter::SUBLAYOUT_CV_PROFILE_CATEGORY_EDIT,
			'user' => $client,
			'cv' => $cv,
			'category' => $category,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function profileCategoryDeleteAction($idCategory)
	{
	
	}
	
	public function profileContentNewAction()
	{
	
	}
	
	public function profileContentEditAction($idContent)
	{
	
	}
	
	public function profileContentDeleteAction($idContent)
	{
	
	}
}