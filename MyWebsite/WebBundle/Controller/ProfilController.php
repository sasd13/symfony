<?php

namespace MyWebsite\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use MyWebsite\WebBundle\Entity\Profil;

class ProfilController extends Controller
{
	private $request = $this->getRequest();
	private $session = $this->getRequest()->getSession();
	private $em = $this->getDoctrine()->getManager();
	
	public function afficherAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();		
		$em = $this->getDoctrine()->getManager();
		
		if ($request->getMethod() == 'POST')
		{
			$admin = $em->getRepository('MyWebsiteWebBundle:Admin')->findOneBy(array('login' => $request->request->get('login'), 'password' => $request->request->get('password')));
			if ($admin == null) return $this->redirect($this->generateUrl('web_login'));
			else
			{
				$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find(1);
				$session->set('idProfil', $profil->getId());
				
				$formBuilder = $this->createFormBuilder($article);
				$formBuilder
					->add('profilLink', 'file')
					->add('pictureDisplay', 'checkbox');
				$form = $formBuilder->getForm();
				
				$layout = 'profil-edit';				
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout, 
																						'profil' => $profil, 
																						'form' => $form->createView()
				));
			}
		}
		else 
		{
			$idProfil = $session->get('idProfil');
	
			if ($idProfil == null) return $this->redirect($this->generateUrl('mywebsiteweb_login'));
			else 
			{
				$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($idProfil);
				
				$formBuilder = $this->createFormBuilder($profil);
				$formBuilder
					->add('pictureDisplay', 'checkbox');
				$form = $formBuilder->getForm();
				
				$layout = 'profil-edit';				
				return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																						'layout' => $layout, 
																						'profil' => $profil,
																						'form' => $form->createView()
				));
			}	
		}
	}
	
	public function nouveauAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$membre = $em->getRepository('MyWebsiteWebBundle:Membre')->findOneBy(array('login' => $request->request->get('login')));
				
		if ($membre == null AND (strcmp($request->request->get('password'), $request->request->get('confirmpassword')) == 0))
		{
			$profil = new Profil();
			$profil->setNom($request->request->get('nom'));
			$profil->setPrenom($request->request->get('prenom'));
			$profil->setEmail($request->request->get('email'));
			$profil->setTelephone($request->request->get('telephone'));
			$profil->setVille($request->request->get('ville'));
			$profil->setPays($request->request->get('pays'));
			
			$membre = new Membre();
			$membre->setLogin($request->request->get('login'));
			$membre->setPassword($request->request->get('password'));
			
			$membre->setProfil($profil);
			
			$em->persist($profil);
			$em->persist($membre);
			$em->flush();
				
			$session = $this->getRequest()->getSession();
			$session->set('idMembre', $membre->getId());
			
			$list_pays = $em->getRepository('MyWebsiteWebBundle:Pays')->myFindAll();
			$layout = 'profil-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array(
																					'layout' => $layout,
																					'membre' => $membre,
																					'list_pays' => $list_pays
			));
		}
		else return $this->redirect($this->generateUrl('mywebsiteweb_register'));
    }
	
	public function modifierAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();
		
		$idProfil = $session->get('idProfil');		
		$profil = $em->getRepository('MyWebsiteWebBundle:Profil')->find($idProfil);
		
		if($profil != null)
		{
			$profil->getProfil()->setPictureLink($request->request->get('pictureLink'));
			$profil->getProfil()->setPictureDisplay($request->request->get('pictureDisplay'));
			
			if (isset($_FILES[$file]) && ($_FILES[$file]['error'] == 0))
			{
				$upload = false;
				$upload_dest = '../users/avatar/'.$user->getAvatar().'.jpg';
				
				if ($_FILES[$file]['size'] <= $maxsize) 
				{
					$infosfichier = pathinfo($_FILES[$file]['name']);
					$extension_upload = $infosfichier['extension'];					
	
					if (in_array($extension_upload, $extensions))
					{
						$upload = move_uploaded_file($_FILES[$file]['tmp_name'], $upload_dest);
					} 
				}
				else
				{
					$msg = "<span class=\"red\">Photo volumineuse</span>";
				}
						
				if($upload == true) 
				{
					$user = $database->selectUserByLogin($user->getLogin());
					$user->setAvatar($upload_dest);
					$query = $database->updateUser($user);
						
					if($query!= false)
					{
						$_SESSION['user'] = serialize($user);
						$msg = "Envoi du fichier \"".$_FILES[$file]['name']."\" r&eacute;ussi";
					}
					else
					{
						$msg = "Photo envoy&eacute;e mais non ajout&eacute; &agrave; la base";
					}
				}
				else
				{
					$msg = "<span class=\"red\">Envoi du fichier \"".$_FILES[$file]['name']."\" &eacute;chou&eacute;</span>";
				}
			}
			
			$em->persist($profil);
			$em->flush();
			
			$layout = 'profil-edit';
			
			return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array'layout' => $layout, 'profil' => $profil));
		}
		else return $this->redirect($this->generateUrl('web_logout'));
    }
	
	public function modifierAdminAction()
    {
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getManager();		
		
		$admin = $em->getRepository('MyWebsiteWebBundle:Administrator')->find(1);
		
		if (($admin->get('password') == $request->request->get('password')) AND ($request->request->get('newpassword') == $request->request->get('confirmnewpassword')))
		{
			$admin->setPassword($request->request->get('newpassword'));
			$em->persist($profil);
			$em->flush();
		}
				
		$layout = 'profil-admin-edit';
		
		return $this->render('MyWebsiteWebBundle:Web:profil.html.twig', array('layout' => $layout));
    }
}
