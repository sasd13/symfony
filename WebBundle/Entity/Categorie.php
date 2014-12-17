<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="mywebsite_categorie")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="tag", type="string", length=255)
     */
    private $tag;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="priorite", type="integer")
     */
    private $priorite;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Cv")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $cv;
	
	public function __construct() 
	{
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
     * Set intitule
     *
     * @param string $intitule
     * @return Categorie
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
     * Set tag
     *
     * @param string $tag
     * @return Categorie
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set priorite
     *
     * @param integer $priorite
     * @return Categorie
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
     * Set cv
     *
     * @param \MyWebsite\WebBundle\Entity\Cv $cv
     * @return Categorie
     */
    public function setCv(\MyWebsite\WebBundle\Entity\Cv $cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get cv
     *
     * @return \MyWebsite\WebBundle\Entity\Cv 
     */
    public function getCv()
    {
        return $this->cv;
    }
}
