<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;
use \DateTime;

class EndLoadFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{		
		$category = $this->getReference('category');
		$category->addDocument($this->getReference('picture'));
		$category->getTimeManager()->setUpdateTime(new DateTime());
		
		$profile = $this->getReference('profile');
		$profile->addCategory($category);
		$profile->getTimeManager()->setUpdateTime(new DateTime());
		
		$manager->persist($profile);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 7; // l'ordre dans lequel les fichiers sont chargés
    }
}