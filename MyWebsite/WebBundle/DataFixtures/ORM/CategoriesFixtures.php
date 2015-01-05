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
		
		$category = new Category("Photo de profil", "picture");
		$category->setTimeManager($timeManager)->setProfile($profile);
		
		$manager->persist($category);
		$manager->flush();
		
		$this->addReference('category', $category);		
	}
	
	public function getOrder()
    {
        return 5; // l'ordre dans lequel les fichiers sont chargés
    }
}