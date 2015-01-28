<?php

namespace MyWebsite\ProfileBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\ProfileBundle\Entity\Client;

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
	
	public function getClientCopy(\MyWebsite\ProfileBundle\Entity\Client $client)
	{
		$webCopy = $this->container->get('web_copy');
		
		$copy = new Client();
		$copy
			->setIdCopy($client->getId())
			->setFirstName($client->getFirstName())
			->setLastName($client->getLastName())
			->setEmail($client->getEmail())
			->setPictureTitle($client->getPictureTitle())
			->setPicturePath($client->getPicturePath())
		;
		foreach($client->getCategories() as $category)
		{
			$copy->addCategory($category);
		}
		
		return $copy;
	}
}
