<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\TimeManager;
use MyWebsite\WebBundle\Entity\Module;

class ModuleFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$moduleWeb = new Module();
		$moduleWeb->setName('Home')
			->setTarget('web_home');
		
		$moduleProfile = new Module();
		$moduleProfile->setName('Profile')
			->setTarget('web_profile');
		
		$manager->persist($moduleWeb);
		$manager->persist($moduleProfile);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}