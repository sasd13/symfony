<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;

class ProfileGenerator
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
		
		//RECORD : Content First Name for Category Info Identity
		$content = new Content(Content::LABEL_PROFILE_FIRSTNAME, 'text');
		$content
			->setLabelValue(Content::LABELVALUE_PROFILE_FIRSTNAME)
			->setStringValue($profile->getFirstName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
			
		//RECORD : Content Last Name for Category Info Identity
		$content = new Content(Content::LABEL_PROFILE_LASTNAME, 'text');
		$content
			->setLabelValue(Content::LABELVALUE_PROFILE_LASTNAME)
			->setStringValue($profile->getLastName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
		
		//RECORD : Category Profile Coordonnees
		$category = new Category('content');
		$category
			->setTitle(Category::TITLE_PROFILE_CONTACT)
			->setTag(Category::TAG_PROFILE_CONTACT)
		;
		$category->setProfile($profile);
		$this->em->persist($category);
		
		//RECORD : Content Email for Category Info Identity
		$content = new Content(Content::LABEL_PROFILE_EMAIL, 'email');
		$content
			->setLabelValue(Content::LABELVALUE_PROFILE_EMAIL)
			->setStringValue($profile->getEmail())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
			
		//RECORD : Content Last Name for Category Info
		$content = new Content('contact_numero_voie', 'text');
		$content
			->setLabelValue('N°')
			->setPlaceholder('1, 2-3, 4 bis...')
			->setCategory($category)
		;
		$this->em->persist($content);
		
		//RECORD : Content Last Name for Category Info
		$content = new Content('contact_libelle_voie', 'text');
		$content
			->setLabelValue('Voie')
			->setPlaceholder('rue de la paix')
			->setCategory($category)
		;
		$this->em->persist($content);
		
		//RECORD : Content Last Name for Category Info
		$content = new Content('contact_code_postal', 'text');
		$content
			->setLabelValue('Code postal')
			->setPlaceholder('75000')
			->setCategory($category)
		;
		$this->em->persist($content);
		
		//RECORD : Content Last Name for Category Info
		$content = new Content('contact_commune', 'text');
		$content
			->setLabelValue('Commune')
			->setPlaceholder('Paris')
			->setCategory($category)
		;
		$this->em->persist($content);
		
		//RECORD : Content Last Name for Category Info
		$content = new Content('contact_pays', 'text');
		$content
			->setLabelValue('Pays')
			->setPlaceholder('France')
			->setCategory($category)
		;
		$this->em->persist($content);
		
		//RECORD : Content First Name for Category Info
		$content = new Content('contact_telephone', 'text');
		$content
			->setLabelValue('Téléphone')
			->setPlaceholder('33 1 23 45 67 89')
			->setCategory($category)
		;
		$this->em->persist($content);
		
		//RECORD : Category Profile Picture
		$category = new Category('document');
		$category
			->setTitle(Category::TITLE_PROFILE_PICTURE)
			->setTag(Category::TAG_PROFILE_PICTURE)
		;
		$category->setProfile($profile);
		$this->em->persist($category);
		
		$this->em->flush();
		
		return $profile;
	}
}
