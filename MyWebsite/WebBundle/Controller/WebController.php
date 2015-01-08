<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WebController extends Controller
{
	public function indexAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$modules = $em->getRepository('MyWebsiteWebBundle:ModuleHandler')->myFindOrdered();
		if($modules == null)
		{
			return $this->redirect($this->generateUrl('web_error'));
		}
		$request->getSession()->set('modules', $modules);
		
		$sessionVars = $request->getSession();
		
		return $this->render('MyWebsiteWebBundle:Web:index.html.twig', array('sessionVars' => $sessionVars));
    }
}
