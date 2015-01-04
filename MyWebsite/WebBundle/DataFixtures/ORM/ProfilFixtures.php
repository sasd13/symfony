<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profil;
use \DateTime;

class ProfilFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profil = new Profil("your first name", "your last name");
		
		$profil->getTimeManager()->setUpdateTime(new DateTime());
		$manager->persist($profil);
		$manager->flush();
		
		$this->addReference('profil', $profil);
	}
	
	public function getOrder()
    {
        return 2; // l'ordre dans lequel les fichiers sont chargés
    }
}