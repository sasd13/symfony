<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cv
 *
 * @ORM\Table(name="mywebsite_cv")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\CvRepository")
 */
class Cv
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="disponibilite", type="string", length=255, nullable=true)
     */
    private $disponibilite;

    /**
     * @var string
     *
     * @ORM\Column(name="mobilite", type="string", length=255, nullable=true)
     */
    private $mobilite;
	
	/**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Profil")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $profil;
	
	public function __construct() 
	{
		$this->description = null;
		$this->disponibilite = null;
		$this->mobilite = null;
		$this->photo = 'port-pic1.jpg';
		$this->actif = false;
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
     * @return Cv
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
     * Set disponibilite
     *
     * @param string $disponibilite
     * @return Cv
     */
    public function setDisponibilite($disponibilite)
    {
        if ($disponibilite === '')  $this->disponibilite = null;
        else $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return string 
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set mobilite
     *
     * @param string $mobilite
     * @return Cv
     */
    public function setMobilite($mobilite)
    {
        if ($mobilite === '')  $this->mobilite = null;
        else $this->mobilite = $mobilite;

        return $this;
    }

    /**
     * Get mobilite
     *
     * @return string 
     */
    public function getMobilite()
    {
        return $this->mobilite;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Cv
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set profil
     *
     * @param \MyWebsite\WebBundle\Entity\Profil $profil
     * @return Cv
     */
    public function setProfil(\MyWebsite\WebBundle\Entity\Profil $profil)
    {
		if ($photo == null) $this->photo = 'port-pic1.jpg';
        else $this->photo = $photo;

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

    /**
     * Set photo
     *
     * @param string $photo
     * @return Cv
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

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
}
