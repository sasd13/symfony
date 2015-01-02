<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\EditManager;

class ProfilFixtures implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profil = new Profil();
		$profil->setEditManager(new EditManager());
		
		$manager->persist($profil);
		$manager->flush();
	}
}