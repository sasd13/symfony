<?php

namespace MyWebsite\ProfileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModuleAdminFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$bundle= $this->getReference('bundle_profile');
		$webRecorder = $this->container->get('web_recorder');
		$router = $this->container->get('profile_router');
		$webData = $this->container->get('web_data');
		$data = $this->container->get('profile_data');
		
		//Module Admin
		$module = $webRecorder->recordModule(
			$bundle,
			'Admin'
		);
		$module->setActive(false);
		
		//MenuWeb Admin
		$menu = $webRecorder->recordMenu(
			$module,
			'Admin', 
			$router::ROUTE_PROFILE_ADMIN, 
			$data::ADMIN_MENU_DISPLAY_WEB
		);
		
		//SubMenus for MenuWeb MyProfile
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Log out',
			$router::ROUTE_PROFILE_USER_LOGOUT
		);
		
		//MenuProfile Profile
		$menu = $webRecorder->recordMenu(
			$module,
			'Profile',
			$router::ROUTE_PROFILE_ADMIN, 
			$data::ADMIN_MENU_DISPLAY_PROFILE
		);
		
		//SubMenu for MenuProfile Profile
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Informations',
			$router::ROUTE_PROFILE_ADMIN_INFO
		);
		
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Log in options',
			$router::ROUTE_PROFILE_USER
		);
		
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Downgrade to Client only',
			$router::ROUTE_PROFILE_USER_DOWNGRADE
		);
	}
	
	public function getOrder()
    {
        return 22; // l'ordre dans lequel les fichiers sont chargés
    }
}