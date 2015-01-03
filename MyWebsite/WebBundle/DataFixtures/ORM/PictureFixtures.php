<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Document;

class PictureFixtures implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profil = $manager->getRepository('MyWebsiteWebBundle:Profil')->find(1);
		
		$category = new Category("Photo de profil", "picture");
		$category->setEditManager($profil->getEditManager());
		
		$picture = new Document();
		$picture->setName("Photo")
			->setMimeType("image/gif")
			->setPath("images/inconnu.gif")
			->setCategory($category);
		
		$manager->persist($category);
		$manager->flush();
	}
}