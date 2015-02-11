<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{	
	public function errorAction()
    {
		$this->container->get('request')->getSession()->clear();

		$check = $this->container->get('web_bundleManager')->checkBundle('Profile');
		if($check === true) {
			$this->container->get('profile_menuGenerator')->setWebMenu();
		}
		else {
			$this->container->get('web_menuGenerator')->setWebMenu();
		}
		
		return $this->render('MyWebsiteWebBundle:Exception:error.html.twig');
    }
	
	public function error404Action()
    {
		$check = $this->container->get('web_bundleManager')->checkBundle('Profile');
		if($check === true) {
			$this->container->get('profile_menuGenerator')->setWebMenu();
		}
		else {
			$this->container->get('web_menuGenerator')->setWebMenu();
		}
		
		return $this->render('MyWebsiteWebBundle:Exception:error404.html.twig');
    }
}
