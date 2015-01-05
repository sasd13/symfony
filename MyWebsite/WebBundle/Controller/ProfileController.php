<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \DateTime;

class ProfileController extends Controller
{
	public function loadProfileAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('modules') == null)
		{
			$modules = $em->getRepository('MyWebsiteWebBundle:ModuleHandler')->myFindOrdered();
			if($modules == null)
			{
				return $this->redirect($this->generateUrl('web_error'));
			}
			$request->getSession()->set('modules', $modules);
		}
		
		$profile = null;
		if($request->getSession()->get('idProfile') == null)
		{
			$user = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($request->request->get('login'));
			if ($request->getMethod() == 'POST' AND $user != null AND strcmp($request->request->get('password'), $user->getPassword()) === 0 AND $user->getPrivacyLevel() == 1)
			{
				$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->findOneByUser($user);
				$request->getSession()->set('idProfile', $profile->getId());
			}
			else
			{
				return $this->render('MyWebsiteWebBundle:Profile:login.html.twig');
			}
		}
		else 
		{
			$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		}
		
		if($profile != null)
		{
			$formProfile = $this->createFormBuilder($profile)
				->setAction($this->generateUrl('web_profile'))
				->setMethod('POST')
				->add('firstName', 'text', array('required' => false))
				->add('lastName', 'text', array('required' => false))
				->add('email', 'email', array('required' => false))
				->getForm();
			
			$arrayOfFormsViewsCategories = null;
			$arrayOfArraysOfFormsViewsContents = null;
			
			//creer myFindCategories
			$categories = $em->getRepository('MyWebsiteWebBundle:Category')->findByTimeManager($profile->getTimeManager()->getId());
			if($categories != null)
			{
				$arrayOfFormsViewsContents = null;
				
				foreach($categories as $category)
				{
					$arrayOfFormsViewsCategories[] = $this->createFormBuilder($category)
						->setAction($this->generateUrl('web_profile'))
						->setMethod('POST')
						->add('title', 'text', array('required' => false))
						->add('tag', 'text', array('required' => false))
						->getForm()
						->createView();
					
					$contents = $category->getContents();
					foreach($contents as $key => $content)
					{
						$arrayOfFormsViewsContents[] = $this->createFormBuilder($content)
							->setAction($this->generateUrl('web_profile'))
							->setMethod('POST')
							->add('title', 'text', array('required' => false))
							->add('tag', 'text', array('required' => false))
							->getForm()
							->createView();
					}
				}
				
				$message = "Les informations n'ont pas été enregistrées";
				if($request->getMethod() == 'POST')
				{
					$profile->getTimeManager()->setUpdateTime(new DateTime());
					$em->persist($profile);
					$em->persist($category);
					$em->flush();
					
					$message = "Les informations ont été enregistrées avec succès";
				}
		
				$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
				return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																							'layout' => 'profile-edit', 
																							'profile' => $profile, 
																							'category' => $category,
																							'formProfile' => $formProfile->createView(),
																							'formCategory' => $formCategory->createView(),
																							'message' => $message
				));
			}
			
			return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																						'layout' => 'profile-edit',
																						'profile' => $profile,
																						'formProfile' => $formProfile->createView(),
																						'categories' => $categories,
																						'formsViewsCategories' => $arrayOfFormsViewsCategories
			));
		}
		
		return $this->redirect($this->generateUrl('web_error'));
	}
	
	public function editProfileAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		if($profile != null)
		{
			$formProfile = $this->createFormBuilder($profile)
				->add('firstName', 'text')
				->add('lastName', 'text')
				->add('email', 'email')
				->getForm();
			$formProfile->handleRequest($request);
			
			$category = $em->getRepository('MyWebsiteWebBundle:Category')->find($request->request->get('idCategory'));
			if($category != null)
			{
				$formCategory = $this->createFormBuilder($category)
					->add('title', 'text')
					->add('tag', 'text')
					->getForm();
				$formCategory->handleRequest($request);
				
				$message = "Les informations n'ont pas été enregistrées";
				if($request->getSession()->get('idProfile') != null)
				{
					$profile->getTimeManager()->setUpdateTime(new DateTime());
					$em->persist($profile);
					$em->persist($category);
					$em->flush();
					
					$message = "Les informations ont été enregistrées avec succès";
				}
		
				$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
				return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																							'layout' => 'profile-edit', 
																							'profile' => $profile, 
																							'category' => $category,
																							'formProfile' => $formProfile->createView(),
																							'formCategory' => $formCategory->createView(),
																							'message' => $message
				));
			}
		}
		
		return $this->redirect($this->generateUrl('web_error'));
    }
	
	public function editPictureAction()
    {
		$request = $this->getRequest();	
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		if($profile != null)
		{
			$category = $em->getRepository('MyWebsiteWebBundle:Category')->findOneByTag('profile_picture');
			if($category != null)
			{
				$documents = $category->getDocuments();
				$picture = $document[0];
				$formPicture = $this->createFormBuilder($picture)
					->add('name')
					->add('file')
					->getForm();
		
				return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																							'layout' => 'profile-picture-edit',
																							'picture' => $picture,
																							'formPicture' => $formPicture->createView()
				));
			}
		}
		
		return $this->redirect($this->generateUrl('web_error'));
	}
	
	public function modifierPictureAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$picture = $em->getRepository('MyWebsiteWebBundle:Document')->find($request->getSession()->get('idPicture'));
		if($picture != null)
		{
			$formPicture = $this->createFormBuilder($document)
				->add('name')
				->add('file')
				->getForm();
			$formPicture->handleRequest($request);
		
			$message = "La photo de profile n'a pas été enregistrée";
			if($request->getSession()->get('idProfile') != null)
			{
				$em->persist($document);
				$em->flush();
				
				$message = "La photo de profile a été enregistrée avec succès";
			}
		
			return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																						'layout' => 'profile-picture-edit',
																						'picture' => $picture,
																						'formPicture' => $formPicture->createView(),
																						'message' => $message
			));
		}
		
		return $this->redirect($this->generateUrl('web_error'));
    }
	
	/**
	 * @Template()
	 */
	public function uploadAction()
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$document = new Document();
		$form = $this->createFormBuilder($document)
			->add('name')
			->add('file')
			->getForm()
		;
		
		if ($this->getRequest()->isMethod('POST')) 
		{
			$form->handleRequest($request);
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
		
				$em->persist($document);
				$em->flush();

				$this->redirect($this->generateUrl('web_profile'));
			}
		}
		
		$layout = 'profile-edit';
		return $this->render('MyWebsiteWebBundle:Web:profile.html.twig', array(
																				'layout' => $layout, 
																				'form' => $formPicture->createView()
		));
	}
	
	public function logoutAction()
    {
		if ($this->getRequest()->getSession()->get('idProfile') != null) $this->getRequest()->getSession()->remove('idProfile');
		
        return $this->redirect($this->generateUrl('web_home'));
    }
}
