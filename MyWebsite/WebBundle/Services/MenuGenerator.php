<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\Menu;

class MenuGenerator
{
	protected $em;
	
	public function setEntityManager(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function generateMenu($type)
	{
		$modules = null;
		
		if($type === 'menu_profile')
		{
			//Bug creating
			//Create new instances of Module
			$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivatedWithMenuByDisplay(Menu::DISPLAY_CONFIG_ONLY);
		}
		else
		{
			//Create new instances of Module
			$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivatedWithMenuByDisplay(Menu::DISPLAY_PUBLIC_ONLY);
		}
		
		//Bug resolver
		//Clear the new instances of Module before persisting
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
}
