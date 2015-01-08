<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\TimeManager;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;
use \DateTime;

class ProfileController extends Controller
{
	public function newProfileAction()
	{
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $em->getRepository('MyWebsiteWebBundle:ModuleHandler')->myFindOrdered();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl('web_error'));
		}
		$request->getSession()->set('modules', $modules);
		
		if($request->getSession()->get('profile') != null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$layout = null;
		$entity = null;
		$form = null;
		$message = "* Denotes Required Field";
		
		if($request->getSession()->get('user') == null)
		{
			$layout = 'Form/signup-user-form';
			
			$entity = new User();
			$form = $this->createFormBuilder($entity)
				->setAction($this->generateUrl('web_signup'))
				->setMethod('POST')
				->add('login', 'text')
				->add('password', 'password')
				->getForm();
		
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
			
				$bufferUser = $em->getRepository('MyWebsiteWebBundle:User')->findByLogin($entity->getLogin());
				if($bufferUser == null)
				{
					if($form->isValid() AND ($entity->getLogin() !== 'login') AND ($entity->getPassword() === $request->request->get('confirmpassword')))
					{
						$request->getSession()->set('user', serialize($entity));
						return $this->redirect($this->generateUrl('web_signup'));
					}
				
					$message = "Informations érronées";
				}
				
				$message = "Identifiant \"".$entity->getLogin()."\" indisponible";
			}
		}
		else
		{
			$layout = 'Form/signup-profile-form';
			
			$user = unserialize($request->getSession()->get('user'));
			$entity = new Profile();
			$form = $this->createFormBuilder($entity)
				->setAction($this->generateUrl('web_signup'))
				->setMethod('POST')
				->add('firstName', 'text')
				->add('lastName', 'text')
				->add('email', 'email')
				->getForm();
			
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
				
				$bufferProfile = $em->getRepository('MyWebsiteWebBundle:Profile')->findByEmail($entity->getEmail());
				if($bufferProfile == null)
				{
					if(($form->isValid()) AND ($entity->getFirstName() !== 'your first name') AND ($entity->getLastName() !== 'your last name') AND ($entity->getEmail() !== 'example@email.com'))
					{
						$user->setTimeManager(new TimeManager());
						$em->persist($user);
						
						$entity->setUser($user);
						$entity->setTimeManager(new TimeManager());
						$em->persist($entity);
						
						$category = new Category('document');
						$category->setTitle('Photo')
							->setTag('profile_picture');
						$category->setProfile($entity);
						$category->setTimeManager(new TimeManager());
						$em->persist($category);
						
						$picture = new Document();
						$picture->setDefault('profile_picture');
						$picture->setCategory($category);
						$em->persist($picture);
						
						$em->flush();
						
						$request->getSession()->remove('user');
						$request->getSession()->set('profile', serialize($entity));
					
						return $this->redirect($this->generateUrl('web_profile'));
					}
				
					$message = "Les informations n'ont pas été enregistrées";
				}
				
				$message = "Email \"".$entity->getEmail()."\" indisponible";
			}
		}	
		
		return $this->render('MyWebsiteWebBundle:Profile:SignUp/signup.html.twig', array(
																							'layout' => $layout,
																							'entity' => $entity,
																							'form' => $form->createView(),
																							'message' => $message
		));
	}
	
	public function loadProfileAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $em->getRepository('MyWebsiteWebBundle:ModuleHandler')->myFindOrdered();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl('web_error'));
		}
		$request->getSession()->set('modules', $modules);
		
		if($request->getSession()->get('profile') == null)
		{
			$user = new User();
				
			$form = $this->createFormBuilder($user)
				->setAction($this->generateUrl('web_profile'))
				->setMethod('POST')
				->add('login', 'text')
				->add('password', 'password')
				->getForm();
			
			$message = "* Denotes Required Field";
			
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
				
				$bufferUser = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($user->getLogin());
				if (($bufferUser != null) AND ($form->isValid()) AND ($bufferUser->getPassword() === $user->getPassword()) AND ($bufferUser->getPrivacyLevel() === 1))
				{
					$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->findOneByUser($bufferUser);
					$request->getSession()->set('profile', serialize($profile));
					
					return $this->redirect($this->generateUrl('web_profile'));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render('MyWebsiteWebBundle:Profile:Login/login.html.twig', array(
																							'form' => $form->createView(),
																							'message' => $message
			));
		}
		
		$profile = unserialize($request->getSession()->get('profile'));
		$categories = new ArrayCollection();
		$arrayOfCategories = $profile->getCategories();
		foreach($arrayOfCategories as $category)
		{
			if($category->getTag() === 'profile_picture')
			{
				$picture = $em->getRepository('MyWebsiteWebBundle:Document')->findOneByCategory($category);
				if($picture->getPath() !== $profile->getPicturePath())
				{
					$profile->setPicturePath($picture->getPath());
					$request->getSession()->set('profile', serialize($profile));
				}
				
				continue;
			}
			
			$bufferCategory = $em->getRepository('MyWebsiteWebBundle:Category')->myFindWithContents($category->getId());
			$categories[] = $bufferCategory;
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																					'layout' => 'profile-edit',
																					'profile' => $profile,
																					'categories' => $categories
		));
	}
	
	public function editProfileAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('profile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		if($request->getMethod() === 'POST')
		{
			$profile = unserialize($request->getSession()->get('profile'));
			$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategories($profile->getId());
			$categories = $profile->getCategories();
			foreach($categories as $bufferCategory)
			{
				$category = $em->getRepository('MyWebsiteWebBundle:Category')->myFindWithContents($bufferCategory->getId());
				$contents = $category->getContents();
				foreach($contents as $content)
				{
					$value = $request->request->get($content->getLabel().'_'.$content->getId());
					
					if($content->getFormType() === 'textarea')
					{
						if($value !== $content->getTextValue())
						{
							$content->setTextValue($value);
							$category->getTimeManager()->setUpdateTime(new DateTime());
						}
					}
					else
					{
						if($value !== $content->getStringValue())
						{
							$content->setStringValue($value);
							$category->getTimeManager()->setUpdateTime(new DateTime());
						}
					}
				
					$em->persist($content);
				}
				
				$em->persist($category);
			}
			
			if($request->request->get('firstName') !== $profile->getFirstName())
			{
				$profile->setFirstName($request->request->get('firstName'));
				$profile->getTimeManager()->setUpdateTime(new DateTime());
			}
			
			if($request->request->get('laststName') !== $profile->getlastName())
			{
				$profile->setlastName($request->request->get('lastName'));
				$profile->getTimeManager()->setUpdateTime(new DateTime());
			}
			
			if($request->request->get('email') !== $profile->getEmail())
			{
				$profile->setEmail($request->request->get('email'));
				$profile->getTimeManager()->setUpdateTime(new DateTime());
			}
			
			$em->persist($profile);
			$em->flush();
			
			$request->getSession()->set('profile', serialize($profile));
			
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		return $this->redirect($this->generateUrl('web_error'));
	}
	
	public function loadPictureAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('profile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = unserialize($request->getSession()->get('profile'));
		
		//-- Modifier
		$picture = $em->getRepository('MyWebsiteWebBundle:Document')->find($profile->getPicture);
		if($picture != null)
		{
			$form = $this->createFormBuilder($document)
				->add('name')
				->add('file')
				->getForm();
			$form->handleRequest($request);
		
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
																						'form' => $form->createView(),
																						'message' => $message
			));
		}
		//--
		
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
																				'form' => $form->createView()
		));
	}
	
	public function loadUserAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('profile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = unserialize($request->getSession()->set('user', serialize($entity)));
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithUser($profile->getId());
		$user = $profile->getUser();
		
		$form = $this->createFormBuilder($user)
			->setAction($this->generateUrl('web_profile_user'))
			->setMethod('POST')
			->add('login', 'text')
			->add('password', 'password')
			->getForm();
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$oldpassword = $user->getPassword();
			$form->handleRequest($request);
			
			$message = "Les informations n'ont pas été enregistrées";
			if(($form->isValid()) AND ($oldpassword === $request->request->get('oldpassword')) AND ($user->getPassword() === $request->request->get('confirmpassword')))
			{
				$user->getTimeManager()->setUpdateTime(new DateTime());
				$em->persist($user);
				$em->flush();
		
				$message = "Les informations ont été enregistrées avec succès";
				$user = $em->getRepository('MyWebsiteWebBundle:User')->find($user->getId());
			}
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
																					'layout' => 'User/profile-user-edit',
																					'profile' => $profile,
																					'form' => $form->createView(),
																					'message' => $message
		));
	}
	
	public function logoutAction()
    {
		if ($this->getRequest()->getSession()->get('profile') != null) $this->getRequest()->getSession()->remove('profile');
		
        return $this->redirect($this->generateUrl('web_home'));
    }
}
