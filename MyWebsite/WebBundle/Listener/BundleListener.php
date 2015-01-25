<?php

namespace MyWebsite\WebBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class BundleListener
{
	/**
     * @var ContainerInterface
     */
    private $container;
	private $resolver;

    public function setParameters(ContainerInterface $container, ControllerResolverInterface $resolver)
    {
        $this->container = $container;
		$this->resolver = $resolver;
    }
	
    public function onKernelController(FilterControllerEvent $event)
	{
		$controller = $event->getController();
		
		if (!is_array($controller)) 
		{
			// not a object but a different kind of callable. Do nothing
			return;
		}
		
		/*
		 * Check Bundle and Module
		 */
		$controllerFullName = get_class($controller[0]);
		// return 'name\bundlenameBundle\Controller\controllernameController'
		
		$check = $this->container->get('web_moduleHandler')->checkHandler($controllerFullName);
		
		if($check === false)
		{
			$fakeRequest = $event->getRequest()->duplicate(null, null, array('_controller' => 'MyWebsiteWebBundle:Web:index'));
			$controller = $this->resolver->getController($fakeRequest);
			$event->setController($controller);
		}
	}
}