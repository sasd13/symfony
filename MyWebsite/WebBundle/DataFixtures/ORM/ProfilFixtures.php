<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profil;

class ProfilFixtures implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profil = new Profil("your first name", "your last name");
		
		$manager->persist($profil);
		$manager->flush();
	}
}