<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WebController extends Controller
{
	public function indexAction()
    {
		$check = $this->container->get('web_bundleManager')->checkBundle('Profile');
		if($check === true) {
			$this->container->get('profile_menuGenerator')->setWebMenu();
		}
		else {
			$this->container->get('web_menuGenerator')->setWebMenu();
		}

		return $this->render('MyWebsiteWebBundle:Web:index.html.twig');
    }
}
