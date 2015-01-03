<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ExceptionController extends Controller
{
	public function error404Action($errorURL)
    {
		return $this->render('MyWebsiteWebBundle:Exception:error.html.twig');
    }
}
