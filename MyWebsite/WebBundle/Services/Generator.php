<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;

class Generator
{
	protected $em;
	
	public function setEntityManager(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function generateProfile(\MyWebsite\WebBundle\Entity\Profile $profile)
	{
		$temp_profile = $this->em->getRepository('MyWebsiteWebBundle:Profile')->findByEmail($profile->getEmail());
		if($temp_profile != null)
		{
			return null;
		}
			
		//RECORD : Profile
		$this->em->persist($profile);
			
		//RECORD : Category Profile Info Identity
		$category = new Category('content');
		$category
			->setTitle(Category::TITLE_PROFILE_INFO)
			->setTag(Category::TAG_PROFILE_INFO)
		;
		$category->setProfile($profile);
		$this->em->persist($category);
		$profile->addCategory($category);
		
		//RECORD : Content First Name for Category Info Identity
		$content = new Content(Content::LABEL_PROFILE_FIRSTNAME, 'text');
		$content
			->setLabelValue(Content::LABELVALUE_PROFILE_FIRSTNAME)
			->setStringValue($profile->getFirstName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
		$category->addContent($content);
			
		//RECORD : Content Last Name for Category Info Identity
		$content = new Content(Content::LABEL_PROFILE_LASTNAME, 'text');
		$content
			->setLabelValue(Content::LABELVALUE_PROFILE_LASTNAME)
			->setStringValue($profile->getLastName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
		$category->addContent($content);
			
		//RECORD : Content Email for Category Info Identity
		$content = new Content(Content::LABEL_PROFILE_EMAIL, 'email');
		$content
			->setLabelValue(Content::LABELVALUE_PROFILE_EMAIL)
			->setStringValue($profile->getEmail())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
		$category->addContent($content);
		/*
		//RECORD : Category Profile Info Degree
		$category = new Category('content');
		$category
			->setTitle('Diplômes')
			->setTag('profile_diplome')
		;
		$category->setProfile($profile);
		$this->em->persist($category);
		$profile->addCategory($category);
		
		//RECORD : Content First Name for Category Info
		$content = new Content('intitule', 'text');
		$content
			->setLabelValue('Intitulé')
			->setStringValue('Licence Mathématiques et Informatique')
			->setCategory($category)
		;
		$this->em->persist($content);
		$category->addContent($content);
			
		//RECORD : Content Last Name for Category Info
		$content = new Content('year', 'number');
		$content
			->setLabelValue('Année')
			->setStringValue('2013')
			->setCategory($category)
		;
		$this->em->persist($content);
		$category->addContent($content);
		*/
		//RECORD : Category Profile Picture
		$category = new Category('document');
		$category
			->setTitle(Category::TITLE_PROFILE_PICTURE)
			->setTag(Category::TAG_PROFILE_PICTURE)
		;
		$category->setProfile($profile);
		$this->em->persist($category);
		$profile->addCategory($category);
		
		$this->em->flush();
		
		return $profile;
	}
}
