<?php

namespace MyWebsite\ProfileBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

class Recorder
{
	protected $em;
	protected $container;
	
	public function setParameters(ObjectManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}
	
	public function createClient(\MyWebsite\ProfileBundle\Entity\Client $client)
	{
		$temp_client = $this->em->getRepository('MyWebsiteProfileBundle:Client')->findByEmail($client->getEmail());
		if($temp_client != null)
		{
			return null;
		}
		
		$webRecorder = $this->container->get('web_recorder');
		$data = $this->container->get('profile_data');
			
		//RECORD : Client
		$this->em->persist($client);
		
		//RECORD : Category Client Info Identity
		$category = $webRecorder->createCategory(
			$client,
			'content',
			$data::USER_CATEGORY_TITLE_INFO,
			$data::USER_CATEGORY_TAG_INFO
		);
		
		//RECORD : Content First Name for Category Info Identity
		$content = $webRecorder->createContent(
			$category,
			$data::USER_CONTENT_LABEL_FIRSTNAME, 
			'text',
			$data::USER_CONTENT_LABELVALUE_FIRSTNAME,
			$client->getFirstName(),
			true,
			null
		);
			
		//RECORD : Content Last Name for Category Info Identity
		$content = $webRecorder->createContent(
			$category,
			$data::USER_CONTENT_LABEL_LASTNAME, 
			'text',
			$data::USER_CONTENT_LABELVALUE_LASTNAME,
			$client->getLastName(),
			true,
			null
		);
		
		//RECORD : Category Client Coordonnees
		$category = $webRecorder->createCategory(
			$client,
			'content',
			$data::USER_CATEGORY_TITLE_CONTACT,
			$data::USER_CATEGORY_TAG_CONTACT
		);
		
		//RECORD : Content Email for Category Contact
		$content = $webRecorder->createContent(
			$category,
			$data::USER_CONTENT_LABEL_EMAIL, 
			'email',
			$data::USER_CONTENT_LABELVALUE_EMAIL,
			$client->getEmail(),
			true,
			null
		);
		
		//RECORD : Content Telephone for Category Contact
		$content = $webRecorder->createContent(
			$category,
			'client_contact_telephone', 
			'text',
			'Téléphone',
			null,
			false,
			'33 1 23 45 67 89'
		);
		
		//RECORD : Category Client Adresse
		$category = $webRecorder->createCategory(
			$client,
			'content',
			'Adresse',
			'client_adress'
		);
		
		//RECORD : Content Numero voie for Category Adresse
		$content = $webRecorder->createContent(
			$category,
			'client_adress_numerovoie', 
			'text',
			'N°',
			null,
			false,
			'1, 2-3, 4 bis...'
		);
				
		//RECORD : Content Voie for Category Adresse
		$content = $webRecorder->createContent(
			$category,
			'client_adress_voie', 
			'text',
			'Voie',
			null,
			false,
			'rue de la paix'
		);
		
		//RECORD : Content Code postale for Category Adresse
		$content = $webRecorder->createContent(
			$category,
			'client_adress_codepostal', 
			'text',
			'Code postal',
			null,
			false,
			'75000'
		);
		
		//RECORD : Content Commune for Category Adresse
		$content = $webRecorder->createContent(
			$category,
			'client_adress_commune', 
			'text',
			'Commune',
			null,
			false,
			'Paris'
		);
		
		//RECORD : Content Pays for Category Adresse
		$content = $webRecorder->createContent(
			$category,
			'client_adress_pays', 
			'text',
			'Pays',
			null,
			false,
			'France'
		);
		
		//RECORD : Category Client Picture
		$category = $webRecorder->createCategory(
			$client,
			'document',
			$data::CLIENT_CATEGORY_TITLE_PICTURE,
			$data::CLIENT_CATEGORY_TAG_PICTURE
		);
		
		return $client;
	}
	
	public function updateClient(\MyWebsite\ProfileBundle\Entity\Client $client, \MyWebsite\ProfileBundle\Entity\Client $clientOld)
	{
		$data = $this->container->get('profile_data');
		
		$categories = $client->getCategories();
		foreach($categories as $keyCategory => $category)
		{
			$contents = $category->getContents();
			foreach($contents as $keyContent => $content)
			{
				$contentOld = $clientOld
					->getCategories()
					->get($keyCategory)
					->getContents()
					->get($keyContent)
				;
					
				if($content->getId() === $contentOld->getIdCopy())
				{
					if($content->getFormType() === 'textarea')
					{
						if($content->getTextValue() !== $contentOld->getTextValue())
						{
							$category->update();
						}
					}
					else
					{
						//Compare values only, not types
						if($content->getStringValue() != $contentOld->getStringValue())
						{
							$category->update();
						}
					}
				}
					
				if($content->getLabel() === $data::USER_CONTENT_LABEL_FIRSTNAME
					AND $content->getStringValue() !== $client->getFirstName())
				{
					$client->setFirstName($content->getStringValue());
					$client->update();
				}
				
				if($content->getLabel() === $data::USER_CONTENT_LABEL_LASTNAME
					AND $content->getStringValue() !== $client->getLastName())
				{
					$client->setLastName($content->getStringValue());
					$client->update();
				}
						
				if($content->getLabel() === $data::USER_CONTENT_LABEL_EMAIL
					AND $content->getStringValue() !== $client->getEmail())
				{
					$client->setEmail($content->getStringValue());
					$client->update();
				}
			}
		}
		
		$this->em->flush();
		
		return true;
	}
}
