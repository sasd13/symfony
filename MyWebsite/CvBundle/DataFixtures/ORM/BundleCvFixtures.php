<?php

namespace MyWebsite\CvBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Bundle;

class BundleCvFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	/**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
	
	public function load(ObjectManager $manager)
	{
		//Bundle Cv
		$bundle = new Bundle('Cv');
		$manager->persist($bundle);
		
		$this->addReference('bundle_cv', $bundle);
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 31; // l'ordre dans lequel les fichiers sont chargés
    }
}