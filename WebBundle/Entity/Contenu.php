<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contenu
 *
 * @ORM\Table(name="mywebsite_contenu")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ContenuRepository")
 */
class Contenu
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description1", type="string", length=255)
     */
    private $description1;

    /**
     * @var string
     *
     * @ORM\Column(name="description2", type="string", length=255, nullable=true)
     */
    private $description2;

    /**
     * @var string
     *
     * @ORM\Column(name="description3", type="text", nullable=true)
     */
    private $description3;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="priorite", type="integer")
     */
    private $priorite;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Categorie")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $categorie;
	
	public function __construct() 
	{
		$this->description2 = null;
		$this->description3 = null;
		$this->priorite = 0;
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
     * Set titre
     *
     * @param string $titre
     * @return Contenu
     */
    public function setTitre1($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre1()
    {
        return $this->titre;
    }

    /**
     * Set description1
     *
     * @param string $description1
     * @return Contenu
     */
    public function setDescription1($description1)
    {
        $this->description1 = $description1;

        return $this;
    }

    /**
     * Get description1
     *
     * @return string 
     */
    public function getDescription1()
    {
        return $this->description1;
    }

    /**
     * Set description2
     *
     * @param string $description2
     * @return Contenu
     */
    public function setDescription2($description2)
    {
        if ($description2 === '')  $this->description2 = null;
        else $this->description2 = $description2;

        return $this;
    }

    /**
     * Get description2
     *
     * @return string 
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Set description3
     *
     * @param string $description3
     * @return Contenu
     */
    public function setDescription3($description3)
    {
        if ($description3 === '')  $this->description3 = null;
        else $this->description3 = $description3;

        return $this;
    }

    /**
     * Get description3
     *
     * @return string 
     */
    public function getDescription3()
    {
        return $this->description3;
    }

    /**
     * Set categorie
     *
     * @param \MyWebsite\WebBundle\Entity\Categorie $categorie
     * @return Contenu
     */
    public function setCategorie(\MyWebsite\WebBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \MyWebsite\WebBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set priorite
     *
     * @param integer $priorite
     * @return Contenu
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }

    /**
     * Get priorite
     *
     * @return integer 
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Contenu
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }
}
