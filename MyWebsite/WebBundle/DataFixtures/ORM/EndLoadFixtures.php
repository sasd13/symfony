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
		$category1 = $this->getReference('category1');
		
		$category1->addContent($this->getReference('content1'));
		$category1->addContent($this->getReference('content2'));
		$category1->getTimeManager()->setUpdateTime(new DateTime());
		
		$category2 = $this->getReference('category2');
		$category2->addDocument($this->getReference('picture'));
		$category2->getTimeManager()->setUpdateTime(new DateTime());
		
		$profile->addCategory($category1);
		$profile->addCategory($category2);
		$profile->getTimeManager()->setUpdateTime(new DateTime());
		
		$manager->persist($profile);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 7; // l'ordre dans lequel les fichiers sont chargés
    }
}