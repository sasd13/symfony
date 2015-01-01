<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cv
 *
 * @ORM\Table(name="web_cv")
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
     * @ORM\Column(name="title", type="string", length=45)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="availability", type="date", nullable=true)
     */
    private $availability;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\MyTime", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $myTime;


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
     * Set availability
     *
     * @param \DateTime $availability
     * @return Cv
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     *
     * @return \DateTime 
     */
    public function getAvailability()
    {
        return $this->availability;
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
     * Set myTime
     *
     * @param \MyWebsite\WebBundle\Entity\MyTime $myTime
     * @return Cv
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
}
