<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Content
 *
 * @ORM\Table(name="web_content")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ContentRepository")
 */
class Content
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
     * @ORM\Column(name="title", type="string", length=45, unique=true)
     */
    private $title;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45)
	 * @Assert\NotBlank
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer")
	 * @Assert\Range(
     *      min = 0,
     *      minMessage = "La priorité doit être au moins de 0"
     * )
     */
    private $priority;

	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Category", inversedBy="contents")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $category;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\MyValue", mappedBy="content", cascade={"persist", "remove"})
	 */
	private $myValues;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\MyText", cascade={"persist", "remove"})
	 */
	private $myText;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\MyDate", cascade={"persist", "remove"})
	 */
	private $myDate;
	
	
	public function __construct($title, $type = "string", $priority = 0)
	{
		$this->title = $title;
		$this->type = $type;
		$this->priority = $priority;
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
     * Set title
     *
     * @param string $title
     * @return Content
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
     * Set type
     *
     * @param string $type
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Content
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set category
     *
     * @param \MyWebsite\WebBundle\Entity\Category $category
     * @return Content
     */
    public function setCategory(\MyWebsite\WebBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \MyWebsite\WebBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add myValues
     *
     * @param \MyWebsite\WebBundle\Entity\MyValue $myValues
     * @return Content
     */
    public function addMyValue(\MyWebsite\WebBundle\Entity\MyValue $myValues)
    {
        $this->myValues[] = $myValues;

        return $this;
    }

    /**
     * Remove myValues
     *
     * @param \MyWebsite\WebBundle\Entity\MyValue $myValues
     */
    public function removeMyValue(\MyWebsite\WebBundle\Entity\MyValue $myValues)
    {
        $this->myValues->removeElement($myValues);
    }

    /**
     * Get myValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyValues()
    {
        return $this->myValues;
    }

    /**
     * Set myText
     *
     * @param \MyWebsite\WebBundle\Entity\MyText $myText
     * @return Content
     */
    public function setMyText(\MyWebsite\WebBundle\Entity\MyText $myText = null)
    {
        $this->myText = $myText;

        return $this;
    }

    /**
     * Get myText
     *
     * @return \MyWebsite\WebBundle\Entity\MyText 
     */
    public function getMyText()
    {
        return $this->myText;
    }

    /**
     * Set myDate
     *
     * @param \MyWebsite\WebBundle\Entity\MyDate $myDate
     * @return Content
     */
    public function setMyDate(\MyWebsite\WebBundle\Entity\MyDate $myDate = null)
    {
        $this->myDate = $myDate;

        return $this;
    }

    /**
     * Get myDate
     *
     * @return \MyWebsite\WebBundle\Entity\MyDate 
     */
    public function getMyDate()
    {
        return $this->myDate;
    }
}
