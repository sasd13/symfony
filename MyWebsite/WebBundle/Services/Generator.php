<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;
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
	
	public function generateMenu($type)
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
			//Bug creating
			//Create new instances of Module by the EntityManager
			$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivatedWithMenusByDisplay(Menu::DISPLAY_PUBLIC_ONLY);
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
	
	public function generateClient(\MyWebsite\WebBundle\Entity\Client $client)
	{
		$temp_client = $this->em->getRepository('MyWebsiteWebBundle:Client')->findByEmail($client->getEmail());
		if($temp_client != null)
		{
			return null;
		}
			
		//RECORD : Client
		$this->em->persist($client);
		
		//RECORD : Category Client Info Identity
		$category = new Category('content');
		$category
			->setTitle(Category::TITLE_PROFILE_INFO)
			->setTag(Category::TAG_PROFILE_INFO)
		;
		$category->setModuleEntity($client);
		$this->em->persist($category);
		
		//RECORD : Content First Name for Category Info Identity
		$content = new Content(Content::LABEL_CLIENT_FIRSTNAME, 'text');
		$content
			->setLabelValue(Content::LABELVALUE_CLIENT_FIRSTNAME)
			->setStringValue($client->getFirstName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
			
		//RECORD : Content Last Name for Category Info Identity
		$content = new Content(Content::LABEL_CLIENT_LASTNAME, 'text');
		$content
			->setLabelValue(Content::LABELVALUE_CLIENT_LASTNAME)
			->setStringValue($client->getLastName())
			->setCategory($category)
			->setRequired(true)
		;
		$this->em->persist($content);
		
		//RECORD : Category Client Coordonnees
		$category = new Category('content');
		$category
			->setTitle(Category::TITLE_PROFILE_CONTACT)
			->setTag(Category::TAG_PROFILE_CONTACT)
		;
		$category->setModuleEntity($client);
		$this->em->persist($category);
		
		//RECORD : Content Email for Category Info Identity
		$content = new Content(Content::LABEL_USER_EMAIL, 'email');
		$content
			->setLabelValue(Content::LABELVALUE_USER_EMAIL)
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
			->setTitle(Category::TITLE_PROFILE_PICTURE)
			->setTag(Category::TAG_PROFILE_PICTURE)
		;
		$category->setModuleEntity($client);
		$this->em->persist($category);
		
		$this->em->flush();
		
		return $client;
	}
}
