<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Module;
use MyWebsite\WebBundle\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;

class ModuleProfileSubModuleAdminFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$router = $this->container->get('web_router');
		
		$module = $this->getReference('module_profile');
		
		//SubModule Admin
		$subModule = new Module('Admin');
		$subModule->setParentModule($module);
		$manager->persist($subModule);
		
		//Public Menu MyProfile
		$menu = new Menu('Admin', $router::ROUTE_ADMIN);
		$menu
			->setIsRoot(true)
			->setModule($subModule)
		;
		$manager->persist($menu);
		
		//SubMenus for Menu MyProfile
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('Log out', $router::ROUTE_PROFILE_LOGOUT);
		$subMenus[] = $sub;
		
		foreach($subMenus as $subMenu)
		{
			
			$subMenu
				->setModule($subModule)
				->setParentMenu($menu)
			;
			$manager->persist($subMenu);
		}
		
		//Config Menu Profile
		$menu = new Menu('Profile', $router::ROUTE_ADMIN);
		$menu
			->setDisplay(Menu::DISPLAY_CONFIG_ONLY)
			->setIsRoot(true)
			->setModule($subModule)
		;
		$manager->persist($menu);
		
		//SubMenu for Menu Profile
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('Informations', $router::ROUTE_ADMIN_INFO);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('Log in options', $router::ROUTE_PROFILE_USER);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		foreach($subMenus as $subMenu)
		{
			
			$subMenu
				->setModule($subModule)
				->setParentMenu($menu)
			;
			$manager->persist($subMenu);
		}
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 2; // l'ordre dans lequel les fichiers sont chargés
    }
}