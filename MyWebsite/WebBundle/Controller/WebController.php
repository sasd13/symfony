<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WebController extends Controller
{
	public function indexAction()
    {
		$router = $this->container->get('web_router');
		$layouter = $this->container->get('web_layouter');
		$request = $this->getRequest();
		
		//Get¨MenuBar
		$menuBar = $this->container->get('web_menu_generator')->generateMenu('menu_bar');
		$request->getSession()->set('menuBar', $menuBar);
		
		return $this->render($layouter::LAYOUT_HOME);
    }
}
