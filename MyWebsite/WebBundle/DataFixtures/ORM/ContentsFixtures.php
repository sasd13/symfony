<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;

class ContentsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$category1 = $manager->getRepository('MyWebsiteWebBundle:Category')->find(1);
		$category2 = $manager->getRepository('MyWebsiteWebBundle:Category')->find(2);
		
		$content1 = new Content("name", $profile->getFirstName());
		$content1->setCategory($category1);
		$content2 = new Content("surname", $profile->getLastName());
		$content2->setCategory($category1);
		
		$picture = new Document();
		$picture->setDefault("image")->setCategory($category2);
		
		$manager->persist($content1);
		$manager->persist($content2);
		$manager->persist($picture);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 6; // l'ordre dans lequel les fichiers sont chargés
    }
}