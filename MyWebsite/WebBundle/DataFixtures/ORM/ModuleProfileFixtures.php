<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Module;
use MyWebsite\WebBundle\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;

class ModuleProfileFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$router = $this->container->get('web_router');
		
		//Module Client
		$module = new Module('Profile');
		$manager->persist($module);
		
		$this->addReference('module_profile', $module);
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}