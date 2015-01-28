<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{	
	public function errorAction()
    {		
		$request = $this->getRequest();
		
		//Services
		$layouter = $this->container->get('web_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		$this->getRequest()->getSession()->clear();
		
		if($request->getSession()->get('mode') === 'admin')
		{
			//Get¨MenuWeb mode Admin
			$menuWeb = $this->container->get('web_generator')->generateMenu(array(
				$webData::DEFAULT_MENU_DISPLAY_WEB,
				$profileData::ADMIN_MENU_DISPLAY_WEB,
			));
		}
		else
		{
			//Get¨MenuWeb mode Client
			$menuWeb = $this->container->get('web_generator')->generateMenu(array(
				$webData::DEFAULT_MENU_DISPLAY_WEB,
				$profileData::CLIENT_MENU_DISPLAY_WEB,
			));
		}
		$request->getSession()->set('menuWeb', $menuWeb);
		//End getting
		
		return $this->render($layouter::LAYOUT_WEB_EXCEPTION_ERROR);
    }
	
	public function error404Action()
    {
		$request = $this->getRequest();
		
		//Services
		$layouter = $this->container->get('web_layouter');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');
		//End services
		
		if($request->getSession()->get('mode') === 'admin')
		{
			//Get¨MenuWeb mode Admin
			$menuWeb = $this->container->get('web_generator')->generateMenu(array(
				$webData::DEFAULT_MENU_DISPLAY_WEB,
				$profileData::ADMIN_MENU_DISPLAY_WEB,
			));
		}
		else
		{
			//Get¨MenuWeb mode Client
			$menuWeb = $this->container->get('web_generator')->generateMenu(array(
				$webData::DEFAULT_MENU_DISPLAY_WEB,
				$profileData::CLIENT_MENU_DISPLAY_WEB,
			));
		}
		$request->getSession()->set('menuWeb', $menuWeb);
		//End getting
		
		return $this->render($layouter::LAYOUT_WEB_EXCEPTION_ERROR404);
    }
}
