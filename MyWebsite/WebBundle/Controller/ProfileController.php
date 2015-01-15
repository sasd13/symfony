<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;
use MyWebsite\WebBundle\Entity\ProfileBuffer;
use MyWebsite\WebBundle\Entity\CategoryBuffer;
use MyWebsite\WebBundle\Entity\ContentBuffer;
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
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		//Verify activated modules
		$modules = $this->container->get('web_moduleHandler')->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_ERROR));
		}
		$request->getSession()->set('modules', $modules);
		
		if($request->getSession()->get('idProfile') != null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
		}
		
		$profile = new Profile();
		
		$form = $this->createForm(new ProfileType(), $profile, array(
			'action' => $this->generateUrl($router::ROUTE_SIGNUP)
		));
			
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "Informations érronées";
			
			$bufferProfile = $em->getRepository('MyWebsiteWebBundle:Profile')->findByLogin($profile->getLogin());
			if(($form->isValid())
				AND ($bufferProfile == null)
				AND ($profile->getPassword() === $request->request->get('confirmPassword')))
			{				
				//Try create Profile with condition on email
				$profile = $this->container->get('web_generator')->generateProfile($profile);
				if($profile != null)
				{
					$request->getSession()->set('idProfile', $profile->getId());
					
					return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
				}
				
				$message = "Email indisponible";
			}
		}	
		
		return $this->render($layouter::LAYOUT_PROFILE_SIGNUP, array(
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message
		));
	}
	
	public function loadProfileAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $this->container->get('web_moduleHandler')->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_ERROR));
		}
		$request->getSession()->set('modules', $modules);
		
		/*
		 * LogIn Action
		 */
		if($request->getSession()->get('idProfile') == null)
		{
			$profile = new Profile();
			
			$form = $this->createForm(new ProfileType(), $profile, array(
				'action' => $this->generateUrl($router::ROUTE_PROFILE)
			));
			
			$message = "* Denotes Required Field";
			
			if($request->getMethod() === 'POST')
			{
				/*
				 * With method handleRequest, the request submit values of all fields present in data even they are missed in form
				 * All missed field have null value, so the form will not validate them with their own assert rules
				 *
				 * This method is used to submit the form in clearing the field that are not present in data
				 * See method submit of FormInterface in Symfony doc
				 */
				$form->submit($request->get($form->getName()), false);
				
				$bufferProfile = $em->getRepository('MyWebsiteWebBundle:Profile')->findOneByLogin($profile->getLogin());
				if (($form->isValid())
					AND ($bufferProfile != null) 
					AND ($bufferProfile->getPassword() === $profile->getPassword()) 
					AND ($bufferProfile->getPrivacyLevel() === Profile::PRIVACYLEVEL_LOW))
				{
					$request->getSession()->set('idProfile', $bufferProfile->getId());
					
					return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
				}
				
				$message = "* Identifiants erronés";
			}
				
			return $this->render($layouter::LAYOUT_PROFILE_LOGIN, array(
				'form' => $form->createView(),
				'message' => $message
			));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Layout/profile-default',
			'profile' => $profile,
		));
	}
	
	public function editProfileAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategoriesAndContents($request->getSession()->get('idProfile'));
		
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
					->setFormType($content->getFormType())
					->setRequired($content->getRequired())
				;
				
				$categoryBuffer->addContent($contentBuffer);
			}
			
			$profileBuffer->addCategory($categoryBuffer);
		}
		
		$form = $this->createForm(new ProfileBufferType(), $profileBuffer, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_INFO)
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
					
					if($categoryBuffer->getId() === $category->getId())
					{
						$contentsBuffer = $categoryBuffer->getContents();
						foreach($contentsBuffer as $keyContent => $contentBuffer)
						{
							$content = $category->getContents()->get($keyContent);
							
							if(($contentBuffer->getId() === $content->getId())
								AND (($contentBuffer->getStringValue() !== $content->getStringValue())
									OR ($contentBuffer->getTextValue() !== $content->getTextValue())))
							{
								if($content->getFormType() === 'textarea')
								{
									$content->setTextValue($contentBuffer->getTextValue());
								}
								else
								{
									$content->setStringValue($contentBuffer->getStringValue());
								}
							
								$category->update();
								
								if(($category->getTag() === Category::TAG_PROFILE_INFO) AND ($content->getLabel() === Content::LABEL_PROFILE_FIRSTNAME))
								{
									$profile->setFirstName($content->getStringValue());
									$profile->update();
								}
							
								if(($category->getTag() === Category::TAG_PROFILE_INFO) AND ($content->getLabel() === Content::LABEL_PROFILE_LASTNAME))
								{
									$profile->setLastName($content->getStringValue());
									$profile->update();
								}
							
								if(($category->getTag() === Category::TAG_PROFILE_INFO) AND ($content->getLabel() === Content::LABEL_PROFILE_EMAIL))
								{
									$profile->setEmail($content->getStringValue());
									$profile->update();
								}
							}
						}
					}
				}
				
				$em->flush();
				
				$message = "Les informations ont été enregistrées";
			}
		}
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Layout/profile-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function editPictureAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategoryAndPicture($request->getSession()->get('idProfile'));
		$category = $profile->getCategories()->get(0);
		$oldPicture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		$picture = new Document('image');
		$form = $this->createForm(new DocumentType(), $picture, array('action' => $this->generateUrl($router::ROUTE_PROFILE_PICTURE)));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->handleRequest($request);
			
			$message = "La photo de profil n'a pas été enregistrée";
			
			if($form->isValid())
			{
				$picture->setCategory($category);
				$em->persist($picture);
				
				if($picture->getPath() !== Document::DEFAULT_PATH)
				{
					if($oldPicture != null)
					{
						$category->removeDocument($oldPicture);
						$em->remove($oldPicture);
					}
					$category->addDocument($picture);
					$category->update();
					
					$profile->setPictureName($picture->getName());
					$profile->setPicturePath($picture->getPath());
					$profile->update();
					
					$message = "La photo de profil a été enregistrée avec succès";
				}
				else
				{
					$em->remove($picture);
				}
				
				$em->flush();
			}
		}
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'Layout/profile-picture-edit',
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
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->myFindWithCategoryAndPicture($request->getSession()->get('idProfile'));
		$category = $profile->getCategories()->get(0);
		$picture = ($category->getDocuments()->count() > 0) ? $category->getDocuments()->get(0) : null;
		
		if(($picture != null) AND is_file($picture->getAbsolutePath()))
		{
			$profile->setPicturePath(null);
			$profile->setPictureName(null);
			
			$category->removeDocument($picture);
			$category->update();
			
			$em->remove($picture);
			
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl($router::ROUTE_PROFILE_PICTURE));
	}
	
	public function editUserAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('idProfile') == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_PROFILE));
		}
		
		$profile = $em->getRepository('MyWebsiteWebBundle:Profile')->find($request->getSession()->get('idProfile'));
		$oldPassword = $profile->getPassword();
		
		$form = $this->createForm(new ProfileType(), $profile, array(
			'action' => $this->generateUrl($router::ROUTE_PROFILE_USER)
		));
		
		$message = "* Denotes Required Field";
		
		if($request->getMethod() === 'POST')
		{
			$form->submit($request->get($form->getName()), false);
			
			$message = "Les informations n'ont pas été enregistrées";
			
			if(($form->isValid()) 
				AND ($oldPassword === $request->request->get('oldPassword')) 
				AND ($profile->getPassword() === $request->request->get('confirmPassword')))
			{
				$profile->update();
				
				$em->flush();
		
				$message = "Les informations ont été enregistrées avec succès";
			}
		}
		
		return $this->render($layouter::LAYOUT_PROFILE, array(
			'subLayout' => 'User/user-edit',
			'profile' => $profile,
			'form' => $form->createView(),
			'message' => $message,
		));
	}
	
	public function logoutAction()
    {
		$router = $this->container->get('web_router');
		
		$this->getRequest()->getSession()->clear();
		
        return $this->redirect($this->generateUrl($router::ROUTE_HOME));
    }
	
	public function deleteProfileAction()
    {
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		return $this->redirect($this->generateUrl($router::ROUTE_ERROR));
	}
}
