<?php

namespace MyWebsite\ProfileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BundleWebFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		//Bundle Web
		$webBundle = $this->container->get('web_recorder')->createBundle('Web');
		
		$this->addReference('bundle_web', $webBundle);
	}
	
	public function getOrder()
    {
        return 11; // l'ordre dans lequel les fichiers sont chargés
    }
}