<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

class ModuleHandler
{
	protected $em;
	private $modules;
	
	public function setEntityManager(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function getActivatedModules()
	{
		$this->modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivated();
		
		return $this->modules;
	}
	
	public function getModules()
	{
		$this->modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindAll();
		
		return $this->modules;
	}
}
