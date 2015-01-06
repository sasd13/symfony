<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\TimeManager;
use MyWebsite\WebBundle\Entity\ModuleHandler;

class ModuleHandlerFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$timeManager = $this->getReference('timeManager1');
		
		$moduleProfile = new ModuleHandler('Profile', 'web_profile', 1);
		$moduleProfile->setTimeManager($timeManager);
		
		$manager->persist($moduleProfile);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 2; // l'ordre dans lequel les fichiers sont chargés
    }
}