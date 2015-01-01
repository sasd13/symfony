<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use MyWebsite\WebBundle\Entity\Pays;

class WebController extends Controller
{
	public function indexAction()
    {		
		return $this->render('MyWebsiteWebBundle:Web:index.html.twig');
    }
	
	public function portfolioAction()
    {
        return $this->render('MyWebsiteWebBundle:Web:portfolio.html.twig');
    }
	
	public function contactAction()
    {
        return $this->render('MyWebsiteWebBundle:Web:contact.html.twig');
    }
	
	public function registerAction()
    {
		$session = $this->getRequest()->getSession();
		$idMembre = $session->get('idMembre');
			
		if ($idMembre != null) return $this->redirect($this->generateUrl('mywebsiteweb_profil'));
		else 
		{
			$request = $this->getRequest();
			
			$em = $this->getDoctrine()->getManager();
			$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
			
			return $this->render('MyWebsiteWebBundle:Web:register.html.twig', array(
																					'list_pays' => $list_pays,
																					'temp_login' => $request->request->get('login'),
																					'temp_email' => $request->request->get('email')
			));
		}
    }
	
	public function loginAction()
    {
		$session = $this->getRequest()->getSession();
		$idMembre = $session->get('idMembre');
			
		if ($idMembre != null) return $this->redirect($this->generateUrl('mywebsiteweb_profil'));
		else return $this->render('MyWebsiteWebBundle:Web:login.html.twig');
    }
	
	public function logoutAction()
    {
		$session = $this->getRequest()->getSession();
		$idMembre = $session->get('idMembre');
		
		if ($idMembre != null) $session->remove('idMembre');
        return $this->redirect($this->generateUrl('mywebsiteweb_home'));
    }
}
