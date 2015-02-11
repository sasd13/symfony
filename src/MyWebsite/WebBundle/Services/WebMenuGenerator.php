<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WebMenuGenerator
{
	protected $em;
	protected $container;
	
	public function setParameters(ObjectManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}

	public function setWebMenu() {
		$webMenu = $this->generateMenu();
		$request = $this->container->get('request');
		$request->getSession()->set('webMenu', $webMenu);
	}

	protected final function generateMenu($arrayDisplay = null)
	{
		$data = $this->container->get('web_data');

		$arrayDisplay = ($arrayDisplay == null)
			? array($data::DEFAULT_MENU_DISPLAY_WEB)
			: $arrayDisplay;

		//Bug creating
		//Create new instances of Module managed by the EntityManager
		$menus = $this->em->getRepository('MyWebsiteWebBundle:Menu')->myFindMenusByDisplay($arrayDisplay);

		//Bug resolver
		//Clear these new instances of Module before persisting
		$this->em->clear();

		return $menus;
	}
}
