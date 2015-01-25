<?php

namespace MyWebsite\ProfileBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

class Generator
{
	protected $em;
	protected $container;
	
	public function setParameters(ObjectManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}
	
	public function generateClient(\MyWebsite\ProfileBundle\Entity\Client $client)
	{
		$temp_client = $this->em->getRepository('MyWebsiteProfileBundle:Client')->findByEmail($client->getEmail());
		if($temp_client != null)
		{
			return null;
		}
		
		$webGenerator = $this->container->get('web_generator');
		$profileData = $this->container->get('profile_data');
			
		//RECORD : Client
		$this->em->persist($client);
		
		//RECORD : Category Client Info Identity
		$category = $webGenerator->generateCategory(
			$client,
			'content',
			$profileData::USER_CATEGORY_TITLE_INFO,
			$profileData::USER_CATEGORY_TAG_INFO
		);
		
		//RECORD : Content First Name for Category Info Identity
		$content = $webGenerator->generateContent(
			$category,
			$profileData::USER_CONTENT_LABEL_FIRSTNAME, 
			'text',
			$profileData::USER_CONTENT_LABELVALUE_FIRSTNAME,
			$client->getFirstName(),
			true,
			null
		);
			
		//RECORD : Content Last Name for Category Info Identity
		$content = $webGenerator->generateContent(
			$category,
			$profileData::USER_CONTENT_LABEL_LASTNAME, 
			'text',
			$profileData::USER_CONTENT_LABELVALUE_LASTNAME,
			$client->getLastName(),
			true,
			null
		);
		
		//RECORD : Category Client Coordonnees
		$category = $webGenerator->generateCategory(
			$client,
			'content',
			$profileData::USER_CATEGORY_TITLE_CONTACT,
			$profileData::USER_CATEGORY_TAG_CONTACT
		);
		
		//RECORD : Content Email for Category Contact
		$content = $webGenerator->generateContent(
			$category,
			$profileData::USER_CONTENT_LABEL_EMAIL, 
			'email',
			$profileData::USER_CONTENT_LABELVALUE_EMAIL,
			$client->getEmail(),
			true,
			null
		);
		
		//RECORD : Content Telephone for Category Contact
		$content = $webGenerator->generateContent(
			$category,
			'client_contact_telephone', 
			'text',
			'Téléphone',
			null,
			false,
			'33 1 23 45 67 89'
		);
		
		//RECORD : Category Client Adresse
		$category = $webGenerator->generateCategory(
			$client,
			'content',
			'Adresse',
			'client_adress'
		);
		
		//RECORD : Content Numero voie for Category Adresse
		$content = $webGenerator->generateContent(
			$category,
			'client_adress_numerovoie', 
			'text',
			'N°',
			null,
			false,
			'1, 2-3, 4 bis...'
		);
				
		//RECORD : Content Voie for Category Adresse
		$content = $webGenerator->generateContent(
			$category,
			'client_adress_voie', 
			'text',
			'Voie',
			null,
			false,
			'rue de la paix'
		);
		
		//RECORD : Content Code postale for Category Adresse
		$content = $webGenerator->generateContent(
			$category,
			'client_adress_codepostal', 
			'text',
			'Code postal',
			null,
			false,
			'75000'
		);
		
		//RECORD : Content Commune for Category Adresse
		$content = $webGenerator->generateContent(
			$category,
			'client_adress_commune', 
			'text',
			'Commune',
			null,
			false,
			'Paris'
		);
		
		//RECORD : Content Pays for Category Adresse
		$content = $webGenerator->generateContent(
			$category,
			'client_adress_pays', 
			'text',
			'Pays',
			null,
			false,
			'France'
		);
		
		//RECORD : Category Client Picture
		$category = $webGenerator->generateCategory(
			$client,
			'document',
			$profileData::CLIENT_CATEGORY_TITLE_PICTURE,
			$profileData::CLIENT_CATEGORY_TAG_PICTURE
		);
		
		return $client;
	}
}
