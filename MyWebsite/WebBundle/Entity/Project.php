<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="web_project")
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
     * @ORM\Column(name="title", type="string", length=45)
     */
    private $title;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\MyTime", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $myTime;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\Period", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $period;


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
     * Set title
     *
     * @param string $title
     * @return Project
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
     * Set myTime
     *
     * @param \MyWebsite\WebBundle\Entity\MyTime $myTime
     * @return Project
     */
    public function setMyTime(\MyWebsite\WebBundle\Entity\MyTime $myTime)
    {
        $this->myTime = $myTime;

        return $this;
    }

    /**
     * Get myTime
     *
     * @return \MyWebsite\WebBundle\Entity\MyTime 
     */
    public function getMyTime()
    {
        return $this->myTime;
    }

    /**
     * Set period
     *
     * @param \MyWebsite\WebBundle\Entity\Period $period
     * @return Project
     */
    public function setPeriod(\MyWebsite\WebBundle\Entity\Period $period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \MyWebsite\WebBundle\Entity\Period 
     */
    public function getPeriod()
    {
        return $this->period;
    }
}
