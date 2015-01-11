<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Module;

class ModuleFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$module = new Module();
		$module
			->setName('Home')
			->setTarget('web_home')
		;
		$manager->persist($module);
		
		$module = new Module();
		$module
			->setName('Profile')
			->setTarget('web_profile')
		;
		$manager->persist($module);
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}