<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Administrator;
use \DateTime;

class AdminFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$admin = new Administrator();
		$admin->setLogin('root');
		$admin->setPassword('root');
		$admin->setEmailBackup(null);
		
		$admin->getEditManager()->setUpdateTime(new DateTime());
		$manager->persist($admin);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}