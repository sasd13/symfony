<?php

namespace MyWebsite\ProfileBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\ProfileBundle\Model\Data;
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
	
	public function generateMenu($type, $moduleName = null)
	{
		$modules = null;
		
		if($type === 'menu_admin')
		{
			$moduleName = 'Admin';
			
			//Bug creating
			//Create new instances of Module managed by the EntityManager
			$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivatedByNameWithMenusByDisplay($moduleName, Menu::DISPLAY_CONFIG_ONLY);
		}
		else if($type === 'menu_client')
		{
			$moduleName = 'Client';
			
			//Bug creating
			//Create new instances of Module managed by the EntityManager
			$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivatedByNameWithMenusByDisplay($moduleName, Menu::DISPLAY_CONFIG_ONLY);
		}
		else
		{
			$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivatedByNameWithMenusByDisplay($moduleName, Menu::DISPLAY_PUBLIC_ONLY);
		}
		
		//Bug resolver
		//Clear these new instances of Module before persisting
		$this->em->clear();
		
		$menuBar = new ArrayCollection();
		foreach($modules as $module)
		{
			$menus = $module->getMenus();
			foreach($menus as $menu)
			{
				$menuBar[] = $menu;
			}
		}
		
		return $menuBar;
	}
	
	public function generateClient(\MyWebsite\ProfileBundle\Entity\Client $client)
	{
		$temp_client = $this->em->getRepository('MyWebsiteProfileBundle:Client')->findByEmail($client->getEmail());
		if($temp_client != null)
		{
			return null;
		}
			
		//RECORD : Client
		$this->em->persist($client);
		
		//RECORD : Category Client Info Identity
		$category = new Category('content');
		$category
			->setTitle(Data::CATEGORY_TITLE_INFO)
			->setTag(Data::CATEGORY_TAG_INFO)
		;
		$category->setModuleEntity($client);
		$this->em->persist($category);
		
		//RECORD : Content First Name for Category Info Identity
		$content = new Content(Data::CONTENT_LABEL_CLIENT_FIRSTNAME, 'text');
		$content
			->setLabelValue(Data::CONTENT_LABELVALUE_CLIENT_FIRSTNAME)
			->setStringValue($client->getFirstName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
			
		//RECORD : Content Last Name for Category Info Identity
		$content = new Content(Data::CONTENT_LABEL_CLIENT_LASTNAME, 'text');
		$content
			->setLabelValue(Data::CONTENT_LABELVALUE_CLIENT_LASTNAME)
			->setStringValue($client->getLastName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
		
		//RECORD : Category Client Coordonnees
		$category = new Category('content');
		$category
			->setTitle(Data::CATEGORY_TITLE_CONTACT)
			->setTag(Data::CATEGORY_TAG_CONTACT)
		;
		$category->setModuleEntity($client);
		$this->em->persist($category);
		
		//RECORD : Content Email for Category Info Identity
		$content = new Content(Data::CONTENT_LABEL_USER_EMAIL, 'email');
		$content
			->setLabelValue(Data::CONTENT_LABELVALUE_USER_EMAIL)
			->setStringValue($client->getEmail())
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
		
		//RECORD : Category Client Picture
		$category = new Category('document');
		$category
			->setTitle(Data::CATEGORY_TITLE_PICTURE)
			->setTag(Data::CATEGORY_TAG_PICTURE)
		;
		$category->setModuleEntity($client);
		$this->em->persist($category);
		
		$this->em->flush();
		
		return $client;
	}
}