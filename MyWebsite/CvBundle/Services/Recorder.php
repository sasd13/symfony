<?php

namespace MyWebsite\CvBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

class Recorder
{
	protected $em;
	protected $container;
	
	public function setParameters(ObjectManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}
	
	public function createCv(\MyWebsite\CvBundle\Entity\Cv $cv)
	{
		$temp_cvs = $this->em->getRepository('MyWebsiteCvBundle:Cv')->findByClient($cv->getClient());
		foreach($temp_cvs as $temp_cv)
		{
			if($temp_cv->getTitle() === $cv->getTitle())
			{
				return null;
			}
		}
		
		$webRecorder = $this->container->get('web_recorder');
		$data = $this->container->get('cv_data');
		
		//RECORD : Cv
		$this->em->persist($cv);
		
		//RECORD : Category Cv Info
		$category = $webRecorder->createCategory(
			$cv,
			'content',
			$data::CV_CATEGORY_TITLE_INFO,
			$data::CV_CATEGORY_TAG_INFO
		);
		
		//RECORD : Content Title for Category Info
		$content = $webRecorder->createContent(
			$category,
			$data::CV_CONTENT_LABEL_TITLE, 
			'text',
			$data::CV_CONTENT_LABELVALUE_TITLE,
			$cv->getTitle(),
			true,
			'exemple : Mon Cv'
		);
		
		//RECORD : Content Description for Category Info
		$content = $webRecorder->createContent(
			$category,
			$data::CV_CONTENT_LABEL_DESCRIPTION, 
			'textarea',
			$data::CV_CONTENT_LABELVALUE_DESCRIPTION,
			$cv->getDescription(),
			false,
			null
		);
		
		//RECORD : Content Disponibility for Category Info
		$content = $webRecorder->createContent(
			$category,
			$data::CV_CONTENT_LABEL_DISPONIBILITY, 
			'text',
			$data::CV_CONTENT_LABELVALUE_DISPONIBILITY, 
			$cv->getDisponibility(),
			false,
			'example : January 2015'
		);
		
		//RECORD : Content Mobility for Category Info
		$content = $webRecorder->createContent(
			$category,
			$data::CV_CONTENT_LABEL_MOBILITY, 
			'text',
			$data::CV_CONTENT_LABELVALUE_MOBILITY, 
			$cv->getMobility(),
			false,
			'example : Paris and Ile-de-France'
		);
		
		return $cv;
	}
	
	public function updateCv(\MyWebsite\CvBundle\Entity\Cv $cv, \MyWebsite\CvBundle\Entity\Cv $cvOld)
	{
		$data = $this->container->get('cv_data');
		
		$categories = $client->getCategories();
		foreach($categories as $keyCategory => $category)
		{
			$contents = $category->getContents();
			foreach($contents as $keyContent => $content)
			{
				$contentOld = $clientOld
					->getCategories()
					->get($keyCategory)
					->getContents()
					->get($keyContent)
				;
					
				if($content->getId() === $contentOld->getIdCopy())
				{
					if($content->getFormType() === 'textarea')
					{
						if($content->getTextValue() !== $contentOld->getTextValue())
						{
							$category->update();
						}
					}
					else
					{
						//Compare values only, not types
						if($content->getStringValue() != $contentOld->getStringValue())
						{
							$category->update();
						}
					}
				}
					
				if($content->getLabel() === $data::CV_CONTENT_LABEL_TITLE
					AND $content->getStringValue() !== $cv->getTitle())
				{
					$cv->setTitle($content->getStringValue());
					$cv->update();
				}
				
				if($content->getLabel() === $data::CV_CONTENT_LABEL_PICTUREPATH
					AND $content->getStringValue() !== $cv->getPicturePath())
				{
					$cv->setPicturePath($content->getStringValue());
					$cv->update();
				}
				
				if($content->getLabel() === $data::CV_CONTENT_LABEL_DESCRIPTION
					AND $content->getStringValue() !== $cv->getDescription())
				{
					$cv->setDescription($content->getTextValue());
					$cv->update();
				}
				
				if($content->getLabel() === $data::CV_CONTENT_LABEL_MOBILITY
					AND $content->getStringValue() !== $cv->getMobility())
				{
					$cv->setMobility($content->getStringValue());
					$cv->update();
				}
			}
		}
		
		$this->em->flush();
		
		return true;
	}
}
