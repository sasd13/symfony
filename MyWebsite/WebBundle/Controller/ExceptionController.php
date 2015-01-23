<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{	
	public function errorAction()
    {
		$layouter = $this->container->get('web_layouter');
		$this->getRequest()->getSession()->clear();
		
		return $this->render($layouter::LAYOUT_WEB_EXCEPTION_ERROR);
    }
	
	public function error404Action($errorURL)
    {
		$layouter = $this->container->get('web_layouter');
		
		return $this->render($layouter::LAYOUT_WEB_EXCEPTION_ERROR404);
    }
}
