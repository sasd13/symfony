<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Document;
use MyWebsite\WebBundle\Entity\Content;

class ContentsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$picture = new Document();
		$picture->setDefault("image")->setCategory($this->getReference('category1'));
		
		$content1 = new Content("Villes", "Paris");
		$content1->setCategory($this->getReference('category2'));
		
		$content2 = new Content("Pays", "France");
		$content2->setCategory($this->getReference('category2'));
		
		$manager->persist($picture);
		$manager->persist($content1);
		$manager->persist($content2);
		$manager->flush();
		
		$this->addReference('picture', $picture);
		$this->addReference('content1', $content1);
		$this->addReference('content2', $content2);
	}
	
	public function getOrder()
    {
        return 6; // l'ordre dans lequel les fichiers sont chargés
    }
}