<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ExceptionController extends Controller
{	
	public function errorAction()
    {
		if ($this->getRequest()->getSession()->get('idProfil') != null) 
		{
			$this->getRequest()->getSession()->remove('idProfil');
			return $this->render('MyWebsiteWebBundle:Exception:error.html.twig');
		}
		return $this->redirect($this->generateUrl('web_profil_afficher'));
    }
	
	public function error404Action($errorURL)
    {
		return $this->render('MyWebsiteWebBundle:Exception:error404.html.twig');
    }
}
