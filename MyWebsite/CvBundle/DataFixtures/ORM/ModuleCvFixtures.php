<?php

namespace MyWebsite\CvBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Module;
use MyWebsite\WebBundle\Entity\Menu;

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
		$router = $this->container->get('cv_router');
		
		$bundle = $this->getReference('bundle_cv');
		
		$module = new Module('Cv');
		$module->setBundle($bundle);
		$manager->persist($module);
		
		//Public Menu CV
		$menu = new Menu('CV', $router::ROUTE_CV_HOME);
		$menu
			->setIsRoot(true)
			->setModule($module)
		;
		$manager->persist($menu);
		
		//Config Menu CV
		$menu = new Menu('CV', $router::ROUTE_PROFILE_CV_LIST);
		$menu
			->setDisplay(Menu::DISPLAY_CONFIG_ONLY)
			->setIsRoot(true)
			->setModule($module)
		;
		$manager->persist($menu);
		
		//SubMenu for Config Menu CV
		$subMenus = new ArrayCollection();
		
		$sub = new Menu('List', $router::ROUTE_PROFILE_CV_LIST);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('New', $router::ROUTE_PROFILE_CV_NEW);
		$sub->setDisplay(Menu::DISPLAY_CONFIG_ONLY);
		$subMenus[] = $sub;
		
		$sub = new Menu('Edit model', $router::ROUTE_PROFILE_CV_MODEL_EDIT);
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
        return 32; // l'ordre dans lequel les fichiers sont chargés
    }
}