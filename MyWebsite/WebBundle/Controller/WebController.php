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
}
