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
		
		$modules = $this->container->get('web_moduleHandler')->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router::ROUTE_ERROR));
		}
		$request->getSession()->set('modules', $modules);
		
		return $this->render($layouter::LAYOUT_HOME);
    }
}
