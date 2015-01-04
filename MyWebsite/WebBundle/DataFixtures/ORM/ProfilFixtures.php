<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\BundleManager;
use MyWebsite\WebBundle\Entity\Profil;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Document;
use \DateTime;

class ProfilFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$bundleManager = new BundleManager("Profil");
		
		$profil = new Profil("your first name", "your last name");
		$profil->setBundleManager($bundleManager);
		
		$category = new Category("Photo de profil", "profil_picture");
		$category->setBundleManager($bundleManager);
		
		$picture = new Document();
		$picture->setDefault("image");
		$picture->setCategory($category);
		
		$manager->persist($bundleManager);
		$manager->persist($profil);
		$category->getTimeManager()->setUpdateTime(new DateTime());
		$manager->persist($picture);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 2; // l'ordre dans lequel les fichiers sont chargés
    }
}