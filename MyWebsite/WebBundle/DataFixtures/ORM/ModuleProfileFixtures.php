<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Module;
use MyWebsite\WebBundle\Entity\Menu;

class ModuleProfileFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		
		//Module Profile
		$module = new Module('Profile');
		$manager->persist($module);
		
		//Public Menu MyProfile
		$menu = new Menu('MyProfile', $router::ROUTE_PROFILE);
		$menu->setIsRoot(true);
		$menu->setModule($module);
		$manager->persist($menu);
		
		//SubMenu for Menu MyProfile
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('SignUp', $router::ROUTE_PROFILE_SIGNUP);
		$subMenus[] = $sub;
		
		$sub = new Menu('LogOut', $router::ROUTE_PROFILE_LOGOUT);
		$subMenus[] = $sub;
		
		foreach($subMenus as $subMenu)
		{
			
			$subMenu->setModule($module);
			$subMenu->setParentMenu($menu);
			$manager->persist($subMenu);
		}
		
		//Config Menu Profile
		$menu = new Menu('Profile', $router::ROUTE_PROFILE);
		$menu
			->setDisplay(Menu::DISPLAY_CONFIG_ONLY)
			->setIsRoot(true)
		;
		$menu->setModule($module);
		$manager->persist($menu);
		
		//SubMenu for Menu Profile
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('Informations', $router::ROUTE_PROFILE_INFO);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('Profile picture', $router::ROUTE_PROFILE_PICTURE);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('LogIn options', $router::ROUTE_PROFILE_USER);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		foreach($subMenus as $subMenu)
		{
			
			$subMenu->setModule($module);
			$subMenu->setParentMenu($menu);
			$manager->persist($subMenu);
		}
		
		$manager->flush();
	}
	
	public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}