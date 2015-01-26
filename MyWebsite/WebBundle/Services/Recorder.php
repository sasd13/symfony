<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Bundle;
use MyWebsite\WebBundle\Entity\Module;
use MyWebsite\WebBundle\Entity\Menu;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;

class Recorder
{
	protected $em;
	protected $container;
	
	public function setParameters(ObjectManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}
	
	public function recordBundle($bundleName)
	{
		$bundle = new Bundle($bundleName);
		
		$this->em->persist($bundle);
		$this->em->flush();
		
		return $bundle;
	}
	
	public function recordModule($bundle, $moduleName)
	{
		$module = new Module($moduleName);
		$module->setBundle($bundle);
		
		$this->em->persist($module);
		$this->em->flush();
		
		return $module;
	}
	
	public function recordMenu($module, $menuName, $menuRoute, $display)
	{
		$menu = new Menu($menuName, $menuRoute);
		$menu
			->setModule($module)
			->setDisplay($display)
			->setIsRoot(true)
		;
		
		$this->em->persist($menu);
		$this->em->flush();
		
		return $menu;
	}
	
	public function recordSubMenu($parentMenu, $subMenuName, $subMenuRoute)
	{
		$subMenu = new Menu($subMenuName, $subMenuRoute);
		$subMenu
			->setParentMenu($parentMenu)
			->setModule($parentMenu->getModule())
		;
		
		$this->em->persist($subMenu);
		$this->em->flush();
		
		return $subMenu;
	}
	
	public function recordCategory($moduleEntity, $type, $title, $tag)
	{
		$category = new Category($type);
		$category
			->setModuleEntity($moduleEntity)
			->setTitle($title)
			->setTag($tag)
		;
		
		$this->em->persist($category);
		$this->em->flush();
		
		return $category;
	}
	
	public function recordContent($category, $label, $formType, $labelValue, $value, $required, $placeholder)
	{
		$content = new Content($label, $formType);
		$content
			->setCategory($category)
			->setLabelValue($labelValue)
			->setRequired($required)
			->setPlaceholder($placeholder)
		;
		if($formType === 'textarea')
		{
			$content->setTextValue($value);
		}
		else
		{
			$content->setStringValue($value);
		}
		
		$this->em->persist($content);
		$this->em->flush();
		
		return $content;
	}
}
