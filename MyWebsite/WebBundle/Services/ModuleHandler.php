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
	
	public function checkHandler($controller)
	{
		$params = explode('::', $controller);
		// $params[0] = 'name\bundlenameBundle\Controller\controllernameController';
		
		$params = explode('\\',$params[0]);
		// $params[1] = 'bundlenameBundle';
		
		$bundleName = substr($params[1],0,-6);
		// $bundleName = 'bundlename';
		
		$bundle = $this->em->getRepository('MyWebsiteWebBundle:Bundle')->findOneByName($bundleName);
		
		$controllerName = substr($params[3],0,-10);
		// $controllerName = 'controllername';
		
		$controllerName = ($controllerName === 'User') ? 'Client' : $controllerName;
		
		$module = ($controllerName === 'User') ? 'None' : $this->em->getRepository('MyWebsiteWebBundle:Module')->findOneByName($controllerName);
		
		if($bundle == null
			OR $bundle->getActive() === false
			OR $module == null
			OR $module->getActive() === false)
		{
			return false;
		}
		
		return true;
	}
	
	public function enableModules($bundleName)
	{
		$bundle = $this->em->getRepository('MyWebsiteWebBundle:Bundle')->findOneByName($bundleName);
		
		$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->findByBundle($bundle);
		foreach($modules as $module)
		{
			$module->setActive(true);
		}
		
		$this->em->flush();
	}
	
	public function disableModules($bundleName)
	{
		$bundle = $this->em->getRepository('MyWebsiteWebBundle:Bundle')->findOneByName($bundleName);
		
		$modules = $this->em->getRepository('MyWebsiteWebBundle:Module')->findByBundle($bundle);
		foreach($modules as $module)
		{
			$module->setActive(false);
		}
		
		$this->em->flush();
	}
}
