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
		$profile = $this->getReference('profile');
		
		$timeManager = $this->getReference('timeManager5');
		$category1 = new Category("Photo de profil", "profile_picture", "document");
		$category1->setTimeManager($timeManager)->setProfile($profile);
		
		$timeManager = $this->getReference('timeManager6');
		$category2 = new Category("Coordonnées", "coordonnees");
		$category2->setTimeManager($timeManager)->setProfile($profile);
		
		$manager->persist($category1);
		$manager->persist($category2);
		$manager->flush();
		
		$this->addReference('category1', $category1);
		$this->addReference('category2', $category2);
	}
	
	public function getOrder()
    {
        return 5; // l'ordre dans lequel les fichiers sont chargés
    }
}