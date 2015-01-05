<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Document;

class ContentsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$picture = new Document();
		$picture->setDefault("image")->setCategory($this->getReference('category'));
		
		$manager->persist($picture);
		$manager->flush();
		
		$this->addReference('picture', $picture);
	}
	
	public function getOrder()
    {
        return 6; // l'ordre dans lequel les fichiers sont chargés
    }
}