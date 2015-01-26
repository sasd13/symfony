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
		$webRecorder = $this->container->get('web_recorder');
		$router = $this->container->get('cv_router');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		//Module Cv
		$module = $webRecorder->createModule(
			$bundle,
			'Cv'
		);
		
		//MenuWeb CV
		$menu = $webRecorder->createMenu(
			$module,
			'CVs', 
			$router::ROUTE_CV_LIST,
			$webData::DEFAULT_MENU_DISPLAY_WEB
		);
		
		//MenuProfile CV
		$menu = $webRecorder->createMenu(
			$module,
			'CV',
			$router::ROUTE_CV_PROFILE_LIST,
			$profileData::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenus for MenuProfile Profile
		$subMenu = $webRecorder->createSubMenu(	
			$menu,
			'List',
			$router::ROUTE_CV_PROFILE_LIST
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'New',
			$router::ROUTE_CV_PROFILE_NEW
		);
	}
	
	public function getOrder()
    {
        return 32; // l'ordre dans lequel les fichiers sont chargés
    }
}