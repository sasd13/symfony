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
		$cvBundle = $this->getReference('bundle_cv');
		$webRecorder = $this->container->get('web_recorder');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		//Module Cv
		$cvModule = $webRecorder->createModule(
			$cvBundle,
			'Cv'
		);
		
		//MenuWeb CV
		$menu = $webRecorder->createMenu(
			$cvModule,
			'CVs',
			'cv_list',
			$webData::DEFAULT_MENU_DISPLAY_WEB
		);
		
		//MenuProfile CV
		$menu = $webRecorder->createMenu(
			$cvModule,
			'CV',
			'cv_profile_list',
			$profileData::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenus for MenuProfile Profile
		$subMenu = $webRecorder->createSubMenu(	
			$menu,
			'List',
			'cv_profile_list'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'New',
			'cv_profile_new'
		);
	}
	
	public function getOrder()
    {
        return 32; // l'ordre dans lequel les fichiers sont chargés
    }
}