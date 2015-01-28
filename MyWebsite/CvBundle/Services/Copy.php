<?php

namespace MyWebsite\CvBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\CvBundle\Entity\Cv;

class Copy
{
	protected $container;
	
	public function setParameters(ContainerInterface $container)
	{
		$this->container = $container;
	}
	
	private function object_to_array($obj) 
	{
		if(is_object($obj)) $obj = (array) $obj;
		if(is_array($obj)) {
			$new = array();
			foreach($obj as $key => $val) {
				$new[$key] = $this->object_to_array($val);
			}
		}
		else $new = $obj;
		return $new;       
	}
	
	public function getArrayCopy($entity)
	{
		return $this->object_to_array($entity);
	}
	
	public function getCvCopy(\MyWebsite\CvBundle\Entity\Cv $cv)
	{
		$webCopy = $this->container->get('web_copy');
		$profileCopy = $this->container->get('profile_copy');
		
		$copy = new Cv();
		$copy
			->setIdCopy($cv->getId())
			->setTitle($cv->getTitle())
			->setPicturePath($cv->getPicturePath())
			->setDescription($cv->getDescription())
			->setDisponibility($cv->getDisponibility())
			->setMobility($cv->getMobility())
			->setActive($cv->getActive())
			->setClient($cv->getClient())
		;
		foreach($cv->getCategories() as $category)
		{
			$copy->addCategory($category);
		}
		
		return $copy;
	}
}
