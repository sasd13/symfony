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
		
		if($request->getSession()->get('idProfile') != null)
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
				if(($bufferUser == null) AND ($form->isValid()) AND ($entity->getLogin() !== 'login') AND ($entity->getPassword() === $request->request->get('confirmpassword')))
					
					$request->getSession()->set('user', $entity);
					return $this->redirect($this->generateUrl('web_signup'));
				}
				
				$message = "Informations érronées";
			}
		}
		else
		{
			$layout = 'Form/signup-profile-form';
			$user = $request->getSession()->get('user');
			
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
				if(($bufferProfile == null) AND ($form->isValid()) AND ($entity->getFirstName() !== 'your first name') AND ($entity->getLastName() !== 'your last name') AND ($entity->getEmail() !== 'example@email.com'))
				{
					$em->persist($user);
					
					$entity->setUser($user);
					$em->persist($entity);
					
					$category = new Category('document');
					$category->setTitle('Photo de profil')
						->setTag('profile_picture');
					$category->setProfile($entity);
					$em->persist($category);
					
					$picture = new Document('picture');
					$picture->setCategory($category);
					$em->persist($picture);
					
					$em->flush();
					
					$request->getSession()->remove('user');
					$request->getSession()->set('idProfile', $entity->getId());
				
					return $this->redirect($this->generateUrl('web_profile'));
				}
				
				$message = "Les informations n'ont pas été enregistrées";
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
		
		if($request->getSession()->get('idProfile') == null)
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
					$request->getSession()->set('idProfile', $profile->getId());
					
					return $this->redirect($this->generateUrl('web_profile'));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render('MyWebsiteWebBundle:Profile:Login/login.html.twig', array(
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		
		$categories = new ArrayCollection();
		$arrayOfCategories = $profile->getCategories();
		foreach($arrayOfCategories as $category)
		{
			if($category->getTag() === 'profile_picture')
			{
				continue;
			}
			
			$bufferCategory = $em->getRepository('MyWebsiteWebBundle:Category')->myFindWithContents($category->getId());
			$categories[] = $bufferCategory;
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'Layout/profile-edit',
			'profile' => $profile,
			'categories' => $categories
		));
	}
	
	public function editProfileAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		if($request->getMethod() === 'POST')
		{
			$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategories($request->getSession()->get('idProfile'));
			
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
						}
					}
					else
					{
						if($value !== $content->getStringValue())
						{
							$content->setStringValue($value);
						}
					}
				
					$em->persist($content);
				}
				
				$em->persist($category);
			}
			
			if($request->request->get('firstName') !== $profile->getFirstName())
			{
				$profile->setFirstName($request->request->get('firstName'));
			}
			
			if($request->request->get('laststName') !== $profile->getlastName())
			{
				$profile->setlastName($request->request->get('lastName'));
			}
			
			if($request->request->get('email') !== $profile->getEmail())
			{
				$profile->setEmail($request->request->get('email'));
			}
			
			$em->persist($profile);
			$em->flush();
			
			$request->getSession()->set('idProfile', $profile->getId());
			
			return $this->redirect($this->generateUrl('web_profile'));
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
		$category = $em->getRepository('MyWebsiteWebBundle:Category')->myFindByCategoryTagAndProfile('profile_picture', $request->getSession()->get('idProfile'));
		$picture = $em->getRepository('MyWebsiteWebBundle:Document')->findOneByCategory($category);
		
		$document = new Document('picture');
		$form = $this->createFormBuilder($document)
			->setAction($this->generateUrl('web_profile_picture'))
			->setMethod('POST')
			->add('name', 'text')
			->add('display', 'checkbox', array(
				'label' => 'display',
			))
			->add('file')
			->getForm();
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
		
			$message = "La photo de profile n'a pas été enregistrée";
			
			if($form->isValid())
			{
				$em->remove($picture);
				$document->setCategory($category);
				$em->persist($document);
				
				$category->addDocument($document);
				$profile->setPictureName($document->getName());
				$profile->setPicturePath($document->getPath());
				$em->persist($category);
				
				$em->flush();
				
				$message = "La photo de profile a été enregistrée avec succès";
			}
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'Layout/profile-picture-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message
		));
    }
	
	public function deletePictureAction()
	{
		return $this->redirect($this->generateUrl('web_profile'));
	}
	
	public function editUserAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl('web_profile'));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithUser($request->getSession()->get('idProfile'));
		
		$user = $profile->getUser();
		$form = $this->createFormBuilder($user)
			->setAction($this->generateUrl('web_profile_user'))
			->setMethod('POST')
			->add('login', 'text', array(
				'read_only' => true,
			))
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
				$em->persist($user);
				$em->flush();
		
				$message = "Les informations ont été enregistrées avec succès";
			}
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'User/user-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function logoutAction()
    {
		if ($this->getRequest()->getSession()->get('idProfile') != null)
		{
			$this->getRequest()->getSession()->clear();;
		}
		
        return $this->redirect($this->generateUrl('web_home'));
    }
}
