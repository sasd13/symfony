<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class WebController extends Controller
{
	public function indexAction()
    {
		return $this->render('MyWebsiteWebBundle:Web:index.html.twig');
    }
	
	public function logoutAction()
    {
		$session = $this->getRequest()->getSession();
		$idProfil = $session->get('idProfil');
		
		if ($idProfil != null) $session->remove('idProfil');
        return $this->render('MyWebsiteWebBundle:Web:index.html.twig');
    }
}
