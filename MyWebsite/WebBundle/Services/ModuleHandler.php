<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

class ModuleHandler
{
	protected $em;
	
	public function setEntityManager(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function getActivatedModules()
	{
		$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindAtivated();
		
		return $modules;
	}
}
