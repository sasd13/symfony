<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="web_category")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=45)
     */
    private $tag;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Cv")
	 */
	private $cv;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Project")
	 */
	private $project;
	
	/**
	 * @ORM\ManyToMany(targetEntity="MyWebsite\WebBundle\Entity\Period", cascade={"persist", "remove"})
	 */
	private $periods;
	

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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->periods = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set cv
     *
     * @param \MyWebsite\WebBundle\Entity\Cv $cv
     * @return Category
     */
    public function setCv(\MyWebsite\WebBundle\Entity\Cv $cv = null)
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

    /**
     * Set project
     *
     * @param \MyWebsite\WebBundle\Entity\Project $project
     * @return Category
     */
    public function setProject(\MyWebsite\WebBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \MyWebsite\WebBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add periods
     *
     * @param \MyWebsite\WebBundle\Entity\Period $periods
     * @return Category
     */
    public function addPeriod(\MyWebsite\WebBundle\Entity\Period $periods)
    {
        $this->periods[] = $periods;

        return $this;
    }

    /**
     * Remove periods
     *
     * @param \MyWebsite\WebBundle\Entity\Period $periods
     */
    public function removePeriod(\MyWebsite\WebBundle\Entity\Period $periods)
    {
        $this->periods->removeElement($periods);
    }

    /**
     * Get periods
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeriods()
    {
        return $this->periods;
    }
}
