<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WebController extends Controller
{
	public function indexAction()
    {
		$moduleHandler = $this->container->get('web_moduleHandler');
		$router = $this->container->get('web_router');
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $moduleHandler->getActivatedModules();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl($router->toError()));
		}
		$request->getSession()->set('modules', $modules);
		
		return $this->render('MyWebsiteWebBundle:Web:index.html.twig');
    }
}
