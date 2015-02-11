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
		$profileBundle = $this->getReference('bundle_profile');
		$webRecorder = $this->container->get('web_recorder');
		$profileData = $this->container->get('profile_data');
		
		//Module Client
		$clientModule = $webRecorder->createModule(
			$profileBundle,
			'Client'
		);
		
		//MenuWeb MyProfile
		$menu = $webRecorder->createMenu(
			$clientModule,
			'MyProfile',
			'profile_client_home',
			$profileData::CLIENT_MENU_DISPLAY_WEB
		);
		$menu->setPriority(99);
		
		//SubMenus for MenuWeb MyProfile
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Sign up',
			'profile_user_signup'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Log out',
			'profile_user_logout'
		);
			
		//MenuProfile Profile
		$menu = $webRecorder->createMenu(
			$clientModule,
			'Profile',
			'profile_client_home',
			$profileData::CLIENT_MENU_DISPLAY_PROFILE
		);
		
		//SubMenu for MenuProfile Profile
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Informations',
			'profile_client_edit'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Profile picture',
			'profile_client_picture_edit'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Log in options',
			'profile_user_edit'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Upgrade to Admin',
			'profile_user_upgrade'
		);
	}
	
	public function getOrder()
    {
        return 23; // l'ordre dans lequel les fichiers sont chargés
    }
}