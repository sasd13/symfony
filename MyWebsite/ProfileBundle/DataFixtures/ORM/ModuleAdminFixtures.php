<?php

namespace MyWebsite\ProfileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Module;
use MyWebsite\WebBundle\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;

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
		$router = $this->container->get('profile_router');
		
		$bundle = $this->getReference('bundle_profile');
		
		//Module Admin
		$module = new Module('Admin');
		$module
			->setBundle($bundle)
			->setActive(false)
		;
		$manager->persist($module);
		
		//Public Menu MyProfile
		$menu = new Menu('Admin', $router::ROUTE_PROFILE_ADMIN);
		$menu
			->setIsRoot(true)
			->setModule($module)
		;
		$manager->persist($menu);
		
		//SubMenus for Menu MyProfile
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('Log out', $router::ROUTE_PROFILE_USER_LOGOUT);
		$subMenus[] = $sub;
		
		foreach($subMenus as $subMenu)
		{
			
			$subMenu
				->setModule($module)
				->setParentMenu($menu)
			;
			$manager->persist($subMenu);
		}
		
		//Config Menu Profile
		$menu = new Menu('Profile', $router::ROUTE_PROFILE_ADMIN);
		$menu
			->setDisplay(Menu::DISPLAY_CONFIG_ONLY)
			->setIsRoot(true)
			->setModule($module)
		;
		$manager->persist($menu);
		
		//SubMenu for Menu Profile
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('Informations', $router::ROUTE_PROFILE_ADMIN_INFO);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('Log in options', $router::ROUTE_PROFILE_USER);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('Downgrade to Client only', $router::ROUTE_PROFILE_USER_DOWNGRADE);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		foreach($subMenus as $subMenu)
		{
			
			$subMenu
				->setModule($module)
				->setParentMenu($menu)
			;
			$manager->persist($subMenu);
		}
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 22; // l'ordre dans lequel les fichiers sont chargés
    }
}