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
		$profile = $this->getReference('profile');
		$category = $this->getReference('category1');
		
		$contents[] = new Content("name", $profile->getFirstName());
		$contents[] = new Content("surname", $profile->getLastName());
		
		for($i = 0; $i < count($contents); $i++)
		{
			$content = $contents[$i];
			$content->setCategory($category);
			$manager->persist($content);
			
			$this->addReference('content'.($i+1), $content);
		}
		
		$category = $this->getReference('category2');
		
		$picture = new Document();
		$picture->setDefault("image")->setCategory($category);
		
		$manager->persist($picture);
		$manager->flush();
		
		$this->addReference('picture', $picture);
	}
	
	public function getOrder()
    {
        return 6; // l'ordre dans lequel les fichiers sont chargés
    }
}