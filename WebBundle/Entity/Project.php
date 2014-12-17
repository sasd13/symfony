<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="mywebsite_project")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ProjectRepository")
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255)
     */
    private $intitule;

    /**
     * @var string
     *
     * @ORM\Column(name="periode", type="string", length=255)
     */
    private $periode;

    /**
     * @var string
     *
     * @ORM\Column(name="technologie", type="string", length=255)
     */
    private $technologie;
	
	/**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
	
	/**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Profil")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $profil;

	public function __construct() 
	{
		$this->website = null;
		$this->photo = 'fea1.png';
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return Project
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set periode
     *
     * @param string $periode
     * @return Project
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return string 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set technologie
     *
     * @param string $technologie
     * @return Project
     */
    public function setTechnologie($technologie)
    {
        $this->technologie = $technologie;

        return $this;
    }

    /**
     * Get technologie
     *
     * @return string 
     */
    public function getTechnologie()
    {
        return $this->technologie;
    }
	
	/**
     * Set website
     *
     * @param string $website
     * @return Project
     */
    public function setWebsite($website)
    {
		if ($website === '')  $this->website = null;
        else $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Set photo
     *
     * @param string $photo
     * @return Project
     */
    public function setPhoto($photo)
    {
		if ($photo == null) $this->photo = 'fea1.png';
        else $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set profil
     *
     * @param \MyWebsite\WebBundle\Entity\Profil $profil
     * @return Project
     */
    public function setProfil(\MyWebsite\WebBundle\Entity\Profil $profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \MyWebsite\WebBundle\Entity\Profil 
     */
    public function getProfil()
    {
        return $this->profil;
    }
}
