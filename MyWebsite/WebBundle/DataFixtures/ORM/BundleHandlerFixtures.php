<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\TimeManager;
use MyWebsite\WebBundle\Entity\BundleHandler;

class BundleHandlerFixtures extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
{
	private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
	
	public function load(ObjectManager $manager)
	{
		$em = $this->container->get('doctrine')->getManager();
		$timeManager = $em->getRepository('MyWebsiteWebBundle:TimeManager')->find(1);

		$bundleHandler = new BundleHandler('Profil');
		$bundleHandler->setTimeManager($timeManager);
		$manager->persist($bundleHandler);
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 2; // l'ordre dans lequel les fichiers sont chargés
    }
}