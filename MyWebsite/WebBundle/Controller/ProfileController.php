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
use MyWebsite\WebBundle\Entity\ProfileBuffer;
use MyWebsite\WebBundle\Entity\CategoryBuffer;
use MyWebsite\WebBundle\Entity\ContentBuffer;
use MyWebsite\WebBundle\Form\UserType;
use MyWebsite\WebBundle\Form\ProfileType;
use MyWebsite\WebBundle\Form\CategoryType;
use MyWebsite\WebBundle\Form\ContentType;
use MyWebsite\WebBundle\Form\DocumentType;
use MyWebsite\WebBundle\Form\ProfileBufferType;
use MyWebsite\WebBundle\Form\CategoryBufferType;
use MyWebsite\WebBundle\Form\ContentBufferType;
use \DateTime;

class ProfileController extends Controller
{
	public function newProfileAction()
	{
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $this->container->get('web_moduleHandler')->getActivatedModules();
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
			$form = $this->createForm(new UserType(), $entity, array(
				'action' => $this->generateUrl($router->toSignup())
			));
		
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
			
				$bufferUser = $em->getRepository('MyWebsiteWebBundle:User')->findByLogin($entity->getLogin());
				if(($bufferUser == null) 
					AND ($form->isValid()) 
					AND ($entity->getPassword() === $request->request->get('confirmpassword')))
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
			$form = $this->createForm(new ProfileType(), $entity, array(
				'action' => $this->generateUrl($router->toSignup())
			));
			
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
				
				//Try create Profile
				$profile = $this->container->get('web_generator')->generateProfile($user, $entity);
				
				if($profile != null)
				{
					$request->getSession()->remove('user');
					$request->getSession()->set('idProfile', $profile->getId());
					
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
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $this->container->get('web_moduleHandler')->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router->toError()));
		}
		$request->getSession()->set('modules', $modules);
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idProfile') == null)
		{
			$user = new User();
			$form = $this->createForm(new UserType(), $user, array(
				'action' => $this->generateUrl($router->toProfile())
			));
			
			$message = "* Denotes Required Field";
			
			if($request->getMethod() === 'POST')
			{
				$form->handleRequest($request);
				
				$bufferUser = $em->getRepository('MyWebsiteWebBundle:User')->findOneByLogin($user->getLogin());
				if (($bufferUser != null) 
					AND ($form->isValid()) 
					AND ($bufferUser->getPassword() === $user->getPassword()) 
					AND ($bufferUser->getPrivacyLevel() === 1))
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
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')
			->myFindWithCategoriesAndContents($request->getSession()
			->get('idProfile'))
		;
		
		//Creating Buffered Form for Profile Information
		$profileBuffer = new ProfileBuffer($profile->getId());
		
		$categories = $profile->getCategories();
		foreach($categories as $category)
		{
			$categoryBuffer = new CategoryBuffer($category->getId());
			
			$contents = $category->getContents();
			foreach($contents as $content)
			{
				$contentBuffer = new ContentBuffer($content->getId());
				if($content->getFormType() === 'textarea')
				{
					$contentBuffer->setTextValue($content->getTextValue());
				}
				else
				{
					$contentBuffer->setStringValue($content->getStringValue());
				}
				$contentBuffer
					->setRequired($content->getRequired())
					->setFormType($content->getFormType())
				;
				
				$categoryBuffer->addContent($contentBuffer);
			}
			
			$profileBuffer->addCategory($categoryBuffer);
		}
		
		$form = $this->createForm(new ProfileBufferType(), $profileBuffer, array(
			'action' => $this->generateUrl($router->toProfileInfo())
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if($form->isValid())
			{
				$categoriesBuffer = $profileBuffer->getCategories();
				foreach($categoriesBuffer as $keyCategory => $categoryBuffer)
				{
					$category = $profile->getCategories()->get($keyCategory);
					
					$contentsBuffer = $categoryBuffer->getContents();
					foreach($contentsBuffer as $keyContent => $contentBuffer)
					{
						$content = $category->getContents()->get($keyContent);
						
						if($contentBuffer->getId() === $content->getId() 
							AND (($contentBuffer->getTextValue() !== $content->getTextValue) OR ($contentBuffer->getStringValue() !== $content->getStringValue)))
						{
							if($content->getFormType() === 'textarea')
							{
								$content->setTextValue($contentBuffer->getTextValue());
								$category->getTimeManager()->setUpdateTime(new DateTime());
							}
							else
							{
								$content->setStringValue($contentBuffer->getStringValue());
							}
							
							$category->getTimeManager()->setUpdateTime(new DateTime());
							
							if(($category->getTag() === 'profile_info') AND ($content->getLabel() === 'first_name'))
							{
								$profile->setFirstName($content->getStringValue());
								$profile->getTimeManager()->setUpdateTime(new DateTime());
							}
							
							if(($category->getTag() === 'profile_info') AND ($content->getLabel() === 'last_name'))
							{
								$profile->setLastName($content->getStringValue());
								$profile->getTimeManager()->setUpdateTime(new DateTime());
							}
							
							if(($category->getTag() === 'profile_info') AND ($content->getLabel() === 'email'))
							{
								$profile->setEmail($content->getStringValue());
								$profile->getTimeManager()->setUpdateTime(new DateTime());
							}
						}
					}
				}
				
				$em->flush();
				
				$message = "Les informations ont été enregistrées";
			}
		}
		
		return $this->render('MyWebsiteWebBundle:Profile:profile.html.twig', array(
			'layout' => 'Layout/profile-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message
		));
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
		$picture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		if(($picture != null) AND is_file($picture->getAbsolutePath()))
		{
			$profile->setPicturePath(null);
			$profile->setPictureName(null);
			
			$category->removeDocument($picture);
			$category->getTimeManager()->setUpdateTime(new DateTime());
			
			unlink($picture->getAbsolutePath());
			$em->remove($picture);
			
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl($router->toProfilePicture()));
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
	
	public function deleteProfileAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		return $this->redirect($this->generateUrl($router->toError()));
	}
	
	
}
