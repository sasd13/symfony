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
		$webGenerator = $this->container->get('web_generator');
		$router = $this->container->get('profile_router');
		$profileData = $this->container->get('profile_data');
		
		//Module Client
		$module = $webGenerator->generateModule(
			$bundle,
			'Client'
		);
		
		//MenuWeb MyProfile
		$menu = $webGenerator->generateMenu(
			$module,
			'MyProfile', 
			$router::ROUTE_PROFILE_CLIENT, 
			$profileData::CLIENT_MENU_DISPLAY_WEB
		);
		
		//SubMenus for MenuWeb MyProfile
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'Sign up',
			$router::ROUTE_PROFILE_USER_SIGNUP
		);
		
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'Log out',
			$router::ROUTE_PROFILE_USER_LOGOUT
		);
			
		//MenuProfile Profile
		$menu = $webGenerator->generateMenu(
			$module,
			'Profile',
			$router::ROUTE_PROFILE_CLIENT, 
			$profileData::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenu for MenuProfile Profile
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'Informations',
			$router::ROUTE_PROFILE_CLIENT_INFO
		);
		
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'Profile picture',
			$router::ROUTE_PROFILE_CLIENT_PICTURE
		);
		
		$subMenu = $webGenerator->generateSubMenu(
			$menu,
			'Log in options',
			$router::ROUTE_PROFILE_USER
		);
		
		$subMenu = $webGenerator->generateSubMenu(
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