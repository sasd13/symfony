<?php

namespace MyWebsite\CvBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModuleCvFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$bundle = $this->getReference('bundle_cv');
		$webGenerator = $this->container->get('web_generator');
		$router = $this->container->get('cv_router');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		//Module Cv
		$module = $webGenerator->generateModule(
			$bundle,
			'Cv'
		);
		
		//MenuWeb CV
		$menu = $webGenerator->generateMenu(
			$module,
			'CV', 
			$router::ROUTE_CV_HOME, 
			$webData::DEFAULT_MENU_DISPLAY_WEB
		);
		
		//MenuProfile CV
		$menu = $webGenerator->generateMenu(
			$module,
			'CV',
			$router::ROUTE_PROFILE_CV, 
			$profileData::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenus for MenuProfile Profile
		$subMenu = $webGenerator->generateSubMenu(	
			$menu,
			'List',
			$router::ROUTE_PROFILE_CV_LIST
		);
		
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'New',
			$router::ROUTE_PROFILE_CV_NEW
		);
	
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'Edit model',
			$router::ROUTE_PROFILE_CV_MODEL_EDIT
		);
	}
	
	public function getOrder()
    {
        return 32; // l'ordre dans lequel les fichiers sont chargés
    }
}