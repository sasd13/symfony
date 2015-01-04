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
		$profile = $manager->getRepository('MyWebsiteWebBundle:Profile')->find(1);
		
		$category1 = $manager->getRepository('MyWebsiteWebBundle:Category')->find(1);
		$content1 = $manager->getRepository('MyWebsiteWebBundle:Content')->find(1);
		$content2 = $manager->getRepository('MyWebsiteWebBundle:Content')->find(2);
		$category1->addContent($content1);
		$category1->addContent($content2);
		$category1->getTimeManager()->setUpdateTime(new DateTime());
		
		$category2 = $manager->getRepository('MyWebsiteWebBundle:Category')->find(2);
		$picture = $manager->getRepository('MyWebsiteWebBundle:Document')->find(1);
		
		$category2->addDocument($picture);
		$category2->getTimeManager()->setUpdateTime(new DateTime());
		$category2->addDocument($picture);
		$category2->getTimeManager()->setUpdateTime(new DateTime());
		
		$profile->addCategory($category1);
		$profile->addCategory($category2);
		$profile->getTimeManager()->setUpdateTime(new DateTime());
		
		$manager->persist($category1);
		$manager->persist($category2);
		$manager->persist($profile);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 7; // l'ordre dans lequel les fichiers sont chargés
    }
}