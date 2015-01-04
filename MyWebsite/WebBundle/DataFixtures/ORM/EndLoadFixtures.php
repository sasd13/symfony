<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profile;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;
use \DateTime;

class EndLoadFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profile = $this->getReference('profile');
		
		$categories[] = $this->getReference('category0');
		$categories[] = $this->getReference('category1');
		
		for($i = 0; $i < count($categories); $i++)
		{
			$category = $categories[$i];
			$category->addContent($this->getReference('content0'));
			$category->addContent($this->getReference('content1'));
			$category->getTimeManager()->setUpdateTime(new DateTime());
			
			$profile->addCategory($category);
			
			$manager->persist($category);
		}
		
		$profile->getTimeManager()->setUpdateTime(new DateTime());
		
		$manager->persist($profile);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 7; // l'ordre dans lequel les fichiers sont chargés
    }
}