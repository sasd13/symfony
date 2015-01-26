<?php

namespace MyWebsite\CvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\ModuleEntity;

/**
 * Cv
 *
 * @ORM\Table(name="cv_cv")
 * @ORM\Entity(repositoryClass="MyWebsite\CvBundle\Entity\CvRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Cv extends ModuleEntity
{	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * @ORM\PreRemove()
     */
    public function preRemove()
    {
        //Control before remove
		//Throw Exception
    }
	
	/**
	 * @ORM\PostRemove()
     */
    public function postRemove()
    {
        //Control before remove
		//Throw Exception
    }
}
