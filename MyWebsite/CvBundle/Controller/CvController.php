<?php

namespace MyWebsite\CvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\CvBundle\Entity\Cv;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

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
		$data =  $this->container->get('cv_data');
		//End services
		
		if($request->getSession()->get('idUser') == null)
		{
			$router = $this->container->get('web_router');
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_CLIENT));
		}
		
		$cv = $em->getRepository('MyWebsiteCvBundle:Cv')->myFindWithCategoriesAndContents($idCv);
		
		if($cv == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_CV_PROFILE_LIST));
		}
		
		$category = new Category('content');
		$category
			->setTitle('New category')
			->setModuleEntity($cv)
		;
		$cv->addCategory($category);
		
		foreach($cv->getCategories() as $key => $category)
		{
			if($key >= 1)
			{
				for($i=0; $i<1; $i++)
				{
					$content = new Content('cv_content_label', 'text');
					$content
						->setCategory($category)
						->setLabelValue('labelValue')
						->setPlaceholder('label : Langues')
					;
					$category->addContent($content);
			
					$content = new Content('cv_content_value', 'text');
					$content
						->setCategory($category)
						->setLabelValue('labelValue')
						->setPlaceholder('value : Anglais')
					;
					$category->addContent($content);
					
							
					$cv->addCategory($category);
				}
			}
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
		
		foreach($cv->getCategories() as $key => $category)
		{
			if($key >= 1)
			{
				for($i=0; $i<1; $i++)
				{
					$content = new Content('cv_content_label', 'text');
					$content
						->setCategory($category)
						->setLabelValue('labelValue')
						->setPlaceholder('label : Langues')
					;
					$category->addContent($content);
			
					$content = new Content('cv_content_value', 'text');
					$content
						->setCategory($category)
						->setLabelValue('labelValue')
						->setPlaceholder('value : Anglais')
					;
					$category->addContent($content);
					
							
					$cv->addCategory($category);
				}
			}
		}
		
		$form = $this->createForm('cv_cv', $cv, array(
			'action' => $this->generateUrl($router::ROUTE_CV_PROFILE_EDIT, array(
				'idCv' => $cv->getId()
			))
		));
		
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
	
	public function profileCategoryDeleteAction($idCategory)
	{
	
	}
	
	public function profileContentDeleteAction($idContent)
	{
	
	}
}