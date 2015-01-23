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
		$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->myFindActivated();
		
		return $modules;
	}
	
	public function checkModules()
	{
		$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->findByActive(false);
		foreach($modules as $module)
		{
			$subModules = $module->getSubModules();
			foreach($subModules as $subModule)
			{
				$subModule->setActive(false);
			}
		}
		
		$this->em->flush();
	}
}
