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
	const DEFAULT_ACTIVE = false;
	
	/**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $title;
	
	/**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;
	
	/**
     * @var string
     *
     * @ORM\Column(name="picturePath", type="string", length=255, nullable=true)
     */
    private $picturePath;
	
	/**
     * @var string
     *
     * @ORM\Column(name="disponibility", type="string", length=255, nullable=true)
     */
    private $disponibility;
	
	/**
     * @var string
     *
     * @ORM\Column(name="mobility", type="string", length=255, nullable=true)
     */
    private $mobility;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $active;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\ProfileBundle\Entity\Client", inversedBy="cvs")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $client;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->active = self::DEFAULT_ACTIVE;
	}
	
	/**
	 * @ORM\PostPersist()
     */
    public function postPersist()
    {
        $this->client->addCv($this);
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

    /**
     * Set title
     *
     * @param string $title
     * @return Cv
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set picturePath
     *
     * @param string $picturePath
     * @return Cv
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    /**
     * Get picturePath
     *
     * @return string 
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Cv
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set disponibility
     *
     * @param string $disponibility
     * @return Cv
     */
    public function setDisponibility($disponibility)
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    /**
     * Get disponibility
     *
     * @return string 
     */
    public function getDisponibility()
    {
        return $this->disponibility;
    }

    /**
     * Set mobility
     *
     * @param string $mobility
     * @return Cv
     */
    public function setMobility($mobility)
    {
        $this->mobility = $mobility;

        return $this;
    }

    /**
     * Get mobility
     *
     * @return string 
     */
    public function getMobility()
    {
        return $this->mobility;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Cv
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
	
	/**
     * Set client
     *
     * @param \MyWebsite\ProfileBundle\Entity\Client $client
     * @return Category
     */
    public function setClient(\MyWebsite\ProfileBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \MyWebsite\ProfileBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
}
