<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class WebController extends Controller
{
	public function indexAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		if($request->getSession()->get('modules') == null)
		{
			$modules = $em->getRepository('MyWebsiteWebBundle:ModuleHandler')->myFindOrdered();
			if($modules == null)
			{
				return $this->redirect($this->generateUrl('web_error'));
			}
			$request->getSession()->set('modules', $modules);
		}
		
		return $this->render('MyWebsiteWebBundle:Web:index.html.twig');
    }
}
