<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;
use MyWebsite\WebBundle\Form\UserType;
use MyWebsite\WebBundle\Form\ProfileType;
use MyWebsite\WebBundle\Form\CategoryType;
use MyWebsite\WebBundle\Form\ContentType;
use MyWebsite\WebBundle\Form\DocumentType;
use \DateTime;

class ProfileController extends Controller
{
	public function newProfileAction()
	{
		$moduleHandler = $this->container->get('web_moduleHandler');
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $moduleHandler->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router->toError()));
		}
		$request->getSession()->set('modules', $modules);
		
		if($request->getSession()->get('idProfile') != null)
		{
			return $this->redirect($this->generateUrl($router->toProfile()));
		}
		
		$layout = null;
		$entity = null;
		$form = null;
		
		$message = "* Denotes Required Field";
		
		if($request->getSession()->get('user') == null)
		{
			$layout = 'Form/signup-user-form';
			
			$entity = new User();
			$form = $this->createForm(new UserType(), $entity, array('action' => $this->generateUrl($router->toSignup())));
		
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
			
				$bufferUser = $em->getRepository('MyWebsiteWebBundle:User')->findByLogin($entity->getLogin());
				if(($bufferUser == null) AND ($form->isValid()) AND ($entity->getPassword() === $request->request->get('confirmpassword')))
				{
					$request->getSession()->set('user', $entity);
					return $this->redirect($this->generateUrl($router->toSignup()));
				}
				
				$message = "Informations érronées";
			}
		}
		else
		{
			$layout = 'Form/signup-profile-form';
			$user = $request->getSession()->get('user');
			
			$entity = new Profile();
			$form = $this->createForm(new ProfileType(), $entity, array('action' => $this->generateUrl($router->toSignup())));
			
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
				
				$bufferProfile = $em->getRepository('MyWebsiteWebBundle:Profile')->findByEmail($entity->getEmail());
				if(($bufferProfile == null) AND ($form->isValid()))
				{
					$em->persist($user);
					
					$entity->setUser($user);
					$em->persist($entity);

					$category = new Category('document');
					$category->setTitle('Photo de profil')
						->setTag('profile_picture');
					$category->setProfile($entity);
					$em->persist($category);
					
					$entity->addCategory($category);
			
					$em->flush();
					
					$request->getSession()->remove('user');
					$request->getSession()->set('idProfile', $entity->getId());
				
					return $this->redirect($this->generateUrl($router->toProfile()));
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
		$moduleHandler = $this->container->get('web_moduleHandler');
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $moduleHandler->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router->toError()));
		}
		$request->getSession()->set('modules', $modules);
		
		if($request->getSession()->get('idProfile') == null)
		{
			$user = new User();
			$form = $this->createForm(new UserType(), $user, array('action' => $this->generateUrl($router->toProfile())));
			
			$message = "* Denotes Required Field";
			
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
				
				$bufferUser = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($user->getLogin());
				if (($bufferUser != null) AND ($form->isValid()) AND ($bufferUser->getPassword() === $user->getPassword()) AND ($bufferUser->getPrivacyLevel() === 1))
				{
					$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->findOneByUser($bufferUser);
					$request->getSession()->set('idProfile', $profile->getId());
					
					return $this->redirect($this->generateUrl($router->toProfile()));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render('MyWebsiteWebBundle:Profile:Login/login.html.twig', array(
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		
		/*
		$category = new Category('content');
		$category->setTitle('Coordonnées')
			->setTag('coordonnees')
			->setProfile($profile);
		$em->persist($category);
		$profile->addCategory($category);
		
		$content = new Content('adresse', 'text');
		$content->setLabelValue('Adresse')
			->setStringValue('41 rue du Long Sentier 93300 Aubervilliers France')
			->setCategory($category);
		$em->persist($content);
		$category->addContent($content);
		
		$content = new Content('email', 'email');
		$content->setLabelValue('Email')
			->setStringValue('ab001@hotmail.fr')
			->setCategory($category);
		$em->persist($content);
		$category->addContent($content);
			
		
		$category = new Category('content');
		$category->setTitle('Formations')
			->setTag('formation')
			->setProfile($profile);
		$em->persist($category);
		$profile->addCategory($category);
		
		$content = new Content('diplome', 'text');
		$content->setLabelValue('Diplôme')
			->setStringValue('Licence')
			->setCategory($category);
		$em->persist($content);
		$category->addContent($content);
		
		$content = new Content('annee', 'number');
		$content->setLabelValue('Année')
			->setStringValue('2013')
			->setCategory($category);
		$em->persist($content);
		$category->addContent($content);
		
		$content = new Content('description', 'textarea');
		$content->setLabelValue('Description')
			->setTextValue('Description de la formation')
			->setCategory($category);
		$em->persist($content);
		$category->addContent($content);
		
		
		$em->flush();
		*/
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'Layout/profile-default',
			'profile' => $profile,
		));
	}
	
	public function editProfileAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router->toProfile()));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategoriesAndContents($request->getSession()->get('idProfile'));
		$form = $this->createForm(new ProfileType(), $profile, array('action' => $this->generateUrl($router->toProfileInfo())));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			//if($form->isValid())
			//{
				$profile->getTimeManager()->setUpdateTime(new DateTime());
				$em->flush();
				
				$message = "Les informations ont été enregistrées";
			//}
			
			//$message = $form->getErrors();
		}
			
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'Layout/profile-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function deleteAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router->toProfile()));
		}
		
		if($request->getMethod() === 'POST')
		{
			$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategoriesAndContents($request->getSession()->get('idProfile'));
			
			$categories = $profile->getCategories();
			foreach($categories as $category)
			{
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
				}
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
			
			$em->flush();
			
			$request->getSession()->set('idProfile', $profile->getId());
			
			return $this->redirect($this->generateUrl($router->toProfile()));
		}
		
		return $this->redirect($this->generateUrl($router->toError()));
	}
	
	public function editPictureAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router->toProfile()));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategoryAndPicture($request->getSession()->get('idProfile'));
		$categories = $profile->getCategories();
		$category = $categories[0];
		$oldPicture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		$picture = new Document('image');
		$form = $this->createForm(new DocumentType(), $picture, array('action' => $this->generateUrl($router->toProfilePicture())));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "La photo de profil n'a pas été enregistrée";
			
			if($form->isValid())
			{
				$picture->setCategory($category);
				$em->persist($picture);
				
				if($picture->getPath() !== 'path')
				{
					if($oldPicture != null)
					{
						$em->remove($oldPicture);
					}
					$category->addDocument($picture);
					$category->getTimeManager()->setUpdateTime(new DateTime());
					
					$profile->setPictureName($picture->getName());
					$profile->setPicturePath($picture->getPath());
					$profile->getTimeManager()->setUpdateTime(new DateTime());
					
					$message = "La photo de profil a été enregistrée avec succès";
				}
				else
				{
					$em->remove($picture);
				}
				
				$em->flush();
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
		return $this->redirect($this->generateUrl($router->toProfilePictureDelete()));
	}
	
	public function editUserAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router->toProfile()));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithUser($request->getSession()->get('idProfile'));
		
		$user = $profile->getUser();
		$oldPassword = $user->getPassword();
		$form = $this->createForm(new UserType(), $user, array('action' => $this->generateUrl($router->toProfileUser())));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if(($form->isValid()) AND ($oldPassword === $request->request->get('oldPassword')) AND ($user->getPassword() === $request->request->get('confirmPassword')))
			{
				$user->getTimeManager()->setUpdateTime(new DateTime());
				$em->flush();
		
				$message = "Les informations ont été enregistrées avec succès";
			}
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'User/user-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function logoutAction()
    {
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($this->container->get('web_router')->toHome()));
    }
}
