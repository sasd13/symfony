<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Document;
use \DateTime;

class PictureFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$profil = $this->getReference('profil');
		
		$category = new Category("Photo de profil", "profil_picture");
		$category->setEditManager($profil->getEditManager());
		
		$picture = new Document("Photo", "image/gif", "images/inconnu.gif");
		$picture->setCategory($category);
		
		$category->getEditManager()->setUpdateTime(new DateTime());
		$manager->persist($picture);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 3; // l'ordre dans lequel les fichiers sont chargés
    }
}