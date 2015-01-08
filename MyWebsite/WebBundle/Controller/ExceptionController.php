<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{	
	public function errorAction()
    {
		if ($this->getRequest()->getSession()->get('profile') != null) 
		{
			$this->getRequest()->getSession()->remove('profile');
		}
		return $this->render('MyWebsiteWebBundle:Exception:error.html.twig');
    }
	
	public function error404Action($errorURL)
    {
		return $this->render('MyWebsiteWebBundle:Exception:error404.html.twig');
    }
}
