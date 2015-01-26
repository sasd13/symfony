<?php

namespace MyWebsite\ProfileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModuleClientFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$bundle = $this->getReference('bundle_profile');
		$webRecorder = $this->container->get('web_recorder');
		$router = $this->container->get('profile_router');
		$data = $this->container->get('profile_data');
		
		//Module Client
		$module = $webRecorder->createModule(
			$bundle,
			'Client'
		);
		
		//MenuWeb MyProfile
		$menu = $webRecorder->createMenu(
			$module,
			'MyProfile', 
			$router::ROUTE_PROFILE_CLIENT, 
			$data::CLIENT_MENU_DISPLAY_WEB
		);
		$menu->setPriority(99);
		
		//SubMenus for MenuWeb MyProfile
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Sign up',
			$router::ROUTE_PROFILE_USER_SIGNUP
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Log out',
			$router::ROUTE_PROFILE_USER_LOGOUT
		);
			
		//MenuProfile Profile
		$menu = $webRecorder->createMenu(
			$module,
			'Profile',
			$router::ROUTE_PROFILE_CLIENT, 
			$data::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenu for MenuProfile Profile
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Informations',
			$router::ROUTE_PROFILE_CLIENT_INFO
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Profile picture',
			$router::ROUTE_PROFILE_CLIENT_PICTURE
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Log in options',
			$router::ROUTE_PROFILE_USER
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Upgrade to Admin',
			$router::ROUTE_PROFILE_USER_UPGRADE
		);
	}
	
	public function getOrder()
    {
        return 23; // l'ordre dans lequel les fichiers sont chargés
    }
}