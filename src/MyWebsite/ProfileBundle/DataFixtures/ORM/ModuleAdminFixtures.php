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
		$profileBundle = $this->getReference('bundle_profile');
		$webRecorder = $this->container->get('web_recorder');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		
		//Module Admin
		$adminModule = $webRecorder->createModule(
			$profileBundle,
			'Admin'
		);
		$adminModule->setActive(false);
		
		//MenuWeb Admin
		$menu = $webRecorder->createMenu(
			$adminModule,
			'Admin', 
			'profile_admin_home',
			$profileData::ADMIN_MENU_DISPLAY_WEB
		);
		$menu->setPriority(99);
		
		//SubMenus for MenuWeb MyProfile
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Log out',
			'profile_user_logout'
		);
		
		//MenuProfile Profile
		$menu = $webRecorder->createMenu(
			$adminModule,
			'Profile',
			'profile_admin_home',
			$profileData::ADMIN_MENU_DISPLAY_PROFILE
		);
		
		//SubMenu for MenuProfile Profile
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Informations',
			'profile_admin_edit'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Log in options',
			'profile_user_edit'
		);
		
		$subMenu = $webRecorder->createSubMenu(
			$menu,
			'Downgrade to Client only',
			'profile_user_downgrade'
		);
	}
	
	public function getOrder()
    {
        return 22; // l'ordre dans lequel les fichiers sont chargés
    }
}