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
		$timeManagers[] = $this->getReference('timeManager5');
		$timeManagers[] = $this->getReference('timeManager6');
		
		$categories[] = new Category("Informations personnelles", "infos");
		$categories[] = new Category("Photo de profil", "picture");
		
		for($i = 0; $i < count($categories); $i++)
		{
			$category = $categories[$i];
			
			$category->setTimeManager($timeManagers[$i])->setProfile($profile);
			$manager->persist($category);
			$this->addReference('category'.($i+1), $category);
		}
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 5; // l'ordre dans lequel les fichiers sont chargés
    }
}