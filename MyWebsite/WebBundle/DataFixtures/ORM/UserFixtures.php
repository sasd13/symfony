<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\TimeManager;
use MyWebsite\WebBundle\Entity\User;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$timeManager = $this->getReference('timeManager2');
		
		$admin = new User('root', 'root', 3);
		$admin->setTimeManager($timeManager);
		
		$timeManager = $this->getReference('timeManager3');
		
		$user = new User('sasd13', 'sasori25', 1);
		$user->setTimeManager($timeManager);
		
		$manager->persist($admin);
		$manager->persist($user);
		$manager->flush();
		
		$this->addReference('user', $user);
	}
	
	public function getOrder()
    {
        return 3; // l'ordre dans lequel les fichiers sont chargés
    }
}