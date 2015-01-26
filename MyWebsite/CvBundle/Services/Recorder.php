<?php

namespace MyWebsite\CvBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;

class Recorder
{
	protected $em;
	
	public function setParameters(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function recordCv()
	{
		//
	}
}
