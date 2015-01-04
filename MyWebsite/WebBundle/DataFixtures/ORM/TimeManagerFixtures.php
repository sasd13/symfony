<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\TimeManager;

class TimeManagerFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		for($i = 1; $i <= 5; $i++)
		{
			$timeManager = new TimeManager();
			$manager->persist($timeManager);
			$this->addReference('timeManager'.$i, $timeManager);
		}
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}