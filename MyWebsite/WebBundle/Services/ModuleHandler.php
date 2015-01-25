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
	
	public function checkHandler($controllerFullName)
	{
		// $controllerFullName = 'name\bundlenameBundle\Controller\controllernameController'
		
		$params = explode('\\', $controllerFullName);
		// $params[1] = 'bundlenameBundle';
		
		$bundleName = substr($params[1], 0, -6);
		// $bundleName = 'bundlename';
		
		if($bundleName === 'Web')
		{
			return true;
		}
		
		$bundle = $this->em->getRepository('MyWebsiteWebBundle:Bundle')->findOneByName($bundleName);
		
		$controllerName = substr($params[3], 0, -10);
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
}
