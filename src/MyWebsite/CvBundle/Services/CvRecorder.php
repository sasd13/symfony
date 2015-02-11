<?php

namespace MyWebsite\CvBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

class CvRecorder
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
		
		return $cv;
	}
	
	public function updateCv(\MyWebsite\CvBundle\Entity\Cv $cv, \MyWebsite\CvBundle\Entity\Cv $cvOld)
	{
		$data = $this->container->get('cv_data');
		
		foreach($cv->getCategories() as $keyCategory => $category)
		{
			$categoryOld = $cvOld
				->getCategories()
				->get($keyCategory)
			;
					
			if($category->getId() != null
				AND $category->getId() === $categoryOld->getId())
			{
				$contents = $category->getContents();
				foreach($contents as $keyContent => $content)
				{
					$contentOld = $categoryOld
						->getContents()
						->get($keyContent)
					;
					
					if($content->getId() != null
						AND $content->getId() === $contentOld->getId())
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
					else
					{
						if($content->getStringValue() == null
							AND $content->getTextValue() == null)
						{
							$category->removeContent($content);
							continue;
						}
						else
						{
							$bufferCategory = $this->em->getRepository('MyWebsiteWebBundle:Category')->myFindByContent($category->getId(), $content->getLabel());
							if($bufferCategory == null)
							{
								$this->em->persist($content);
							}
						}
					}
					
					if($content->getLabel() === $data::CV_CONTENT_LABEL_TITLE
						AND $content->getStringValue() !== $cv->getTitle())
					{
						$cv->setTitle($content->getStringValue());
						$cv->update();
					}
				
					if($content->getLabel() === $data::CV_CONTENT_LABEL_DISPONIBILITY
						AND $content->getStringValue() !== $cv->getMobility())
					{
						$cv->setMobility($content->getStringValue());
						$cv->update();
					}
				
					if($content->getLabel() === $data::CV_CONTENT_LABEL_MOBILITY
						AND $content->getStringValue() !== $cv->getMobility())
					{
						$cv->setMobility($content->getStringValue());
						$cv->update();
					}
				
					if($content->getLabel() === $data::CV_CONTENT_LABEL_DESCRIPTION
						AND $content->getStringValue() !== $cv->getDescription())
					{
						$cv->setDescription($content->getTextValue());
						$cv->update();
					}
				}
			}
			else
			{
				if($category->getTitle() != null
					AND $category->getTitle() !== 'New category')
				{
					$bufferCv = $this->em->getRepository('MyWebsiteCvBundle:Cv')->myFindByCategory($cv->getId(), $category->getTitle());
					if($bufferCv == null)
					{
						foreach($category->getContents() as $keyContent => $content)
						{
							$contentOld = $categoryOld
								->getContents()
								->get($keyContent)
							;
							
							if($content->getStringValue() == null
								AND $content->getTextValue() == null)
							{
								$category->removeContent($content);
							}
						}
						
						$this->em->persist($category);
					}
				}
				else
				{
					$cv->removeCategory($category);
					$cvOld->removeCategory($categoryOld);
				}
			}
		}
		
		$this->em->flush();

		for($i = 0; $i<count($cvOld->getCategories()); $i++) {
			if($i >= count($cv->getCategories())) {
				$cv->addCategory($cvOld->getCategories()->get($i));
			}
			else {
				$category = $cv->getCategories()->get($i);
				$categoryOld = $cvOld->getCategories()->get($i);

				for($j = 0; $j<count($categoryOld->getContents()); $j++) {
					$category->addContent($categoryOld->geContents()->get($j));
				}
			}

		}
		return true;
	}
}
