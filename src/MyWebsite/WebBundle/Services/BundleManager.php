<?php

namespace MyWebsite\WebBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

class BundleManager
{
	protected $em;
	
	public function setParameters(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function checkController($controllerFullName)
	{
		// $controllerFullName = 'name\bundlenameBundle\Controller\controllernameController'
		
		$params = explode('\\', $controllerFullName);
		// $params[1] = 'bundlenameBundle';
		
		$bundleName = substr($params[1], 0, -6);
		// $bundleName = 'bundlename';
		
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

	public function checkBundle($bundleName)
	{
		$bundle = $this->em->getRepository('MyWebsiteWebBundle:Bundle')->findOneByName($bundleName);

		if($bundle == null
			OR $bundle->getActive() === false)
		{
			return false;
		}

		return true;
	}
}
