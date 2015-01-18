<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\Menu;

class ModuleHandler
{
	protected $em;
	
	public function setEntityManager(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function getModules()
	{
		$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindAll();
		
		return $modules;
	}
}
