<?php

namespace MyWebsite\WebBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;
use MyWebsite\WebBundle\Entity\Document;

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
	
	public function getCategoryCopy(\MyWebsite\WebBundle\Entity\Category $category)
	{
		$copy = new Category($category->getType());
		$copy
			->setIdCopy($category->getId())
			->setTitle($category->getTitle())
			->setTag($category->getTag())
			->setModuleEntity($category->getModuleEntity())
		;
		foreach($category->getContents() as $content)
		{
			$copy->addContent($content);
		}
		foreach($category->getDocuments() as $document)
		{
			$copy->addDocument($document);
		}
		
		return $copy;
	}
	
	public function getContentCopy(\MyWebsite\WebBundle\Entity\Content $content)
	{
		$copy = new Content($content->getLabel(), $content->getFormType());
		$copy
			->setIdCopy($content->getId())
			->setLabelValue($content->getLabelValue())
			->setStringValue($content->getStringValue())
			->setTextValue($content->getTextValue())
			->setRequired($content->getRequired())
			->setPolicyLevel($content->getPolicyLevel())
			->setPriority($content->getPriority())
			->setPlaceholder($content->getPlaceholder())
			->setCategory($content->getCategory())
		;
		
		return $copy;
	}
	
	public function getDocumentCopy(\MyWebsite\WebBundle\Entity\Document $document)
	{
		$copy = new Document();
		$copy
			->setIdCopy($document->getId())
			->setTitle($document->getTitle())
			->setOriginalName($document->getOriginalName())
			->setMimeType($document->getMimeType())
			->setHide($document->getHide())
			->setPath($document->getPath())
			->setUploadDate($document->getUploadDate())
			->setCategory($document->getCategory())
		;
		
		return $copy;
	}
}
