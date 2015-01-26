<?php

namespace MyWebsite\ProfileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModuleWebFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$bundle = $this->getReference('bundle_web');
		$recorder = $this->container->get('web_recorder');
		$router = $this->container->get('web_router');
		$data = $this->container->get('web_data');
		
		//Module Web
		$module = $recorder->recordModule(
			$bundle,
			'Web'
		);
		
		//MenuWeb Home
		$menu = $recorder->recordMenu(
			$module,
			'Home', 
			$router::ROUTE_WEB_HOME, 
			$data::DEFAULT_MENU_DISPLAY_WEB
		);
	}
	
	public function getOrder()
    {
        return 12; // l'ordre dans lequel les fichiers sont chargés
    }
}