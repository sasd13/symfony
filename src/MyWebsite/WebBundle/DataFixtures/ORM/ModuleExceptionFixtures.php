<?php

namespace MyWebsite\ProfileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModuleExceptionFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$webBundle= $this->getReference('bundle_web');
		$webRecorder = $this->container->get('web_recorder');
		$webData = $this->container->get('web_data');
		
		//Module Exception
		$expModule = $webRecorder->createModule(
			$webBundle,
			'Exception'
		);
	}
	
	public function getOrder()
    {
        return 13; // l'ordre dans lequel les fichiers sont chargés
    }
}