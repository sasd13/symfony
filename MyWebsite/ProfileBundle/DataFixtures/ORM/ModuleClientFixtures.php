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
		$module = $webRecorder->recordModule(
			$bundle,
			'Client'
		);
		
		//MenuWeb MyProfile
		$menu = $webRecorder->recordMenu(
			$module,
			'MyProfile', 
			$router::ROUTE_PROFILE_CLIENT, 
			$data::CLIENT_MENU_DISPLAY_WEB
		);
		
		//SubMenus for MenuWeb MyProfile
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Sign up',
			$router::ROUTE_PROFILE_USER_SIGNUP
		);
		
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Log out',
			$router::ROUTE_PROFILE_USER_LOGOUT
		);
			
		//MenuProfile Profile
		$menu = $webRecorder->recordMenu(
			$module,
			'Profile',
			$router::ROUTE_PROFILE_CLIENT, 
			$data::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenu for MenuProfile Profile
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Informations',
			$router::ROUTE_PROFILE_CLIENT_INFO
		);
		
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Profile picture',
			$router::ROUTE_PROFILE_CLIENT_PICTURE
		);
		
		$subMenu = $webRecorder->recordSubMenu(
			$menu,
			'Log in options',
			$router::ROUTE_PROFILE_USER
		);
		
		$subMenu = $webRecorder->recordSubMenu(
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