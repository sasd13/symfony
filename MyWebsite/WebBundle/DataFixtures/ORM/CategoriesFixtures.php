<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;

class CategoriesFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profile = $manager->getRepository('MyWebsiteWebBundle:Profile')->find(1);
		
		$category1 = new Category("Informations personnelles", "infos");
		$category1->setProfile($profile);
		$category2 = new Category("Photo de profil", "picture");
		$category2->setProfile($profile);
		
		$manager->persist($category1);
		$manager->persist($category2);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 5; // l'ordre dans lequel les fichiers sont chargés
    }
}