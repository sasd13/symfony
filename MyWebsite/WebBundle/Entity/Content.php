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
     * @ORM\Column(name="label", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $label;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
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
     * @var integer
     *
     * @ORM\Column(name="policyLevel", type="smallint")
	 * @Assert\Range(
     *      min = 1,
	 *      max = 3,
     *      minMessage = "La priorité doit être plus de {{ limit }}",
	 *      maxMessage = "La priorité doit être moins de {{ limit }}"
     * )
     */
    private $policyLevel;
	
	/**
     * @var string
     *
     * @ORM\Column(name="stringValue", type="string", length=255, nullable=true)
     */
    private $stringValue;
	
	/**
     * @var string
     *
     * @ORM\Column(name="textValue", type="text", nullable=true)
     */
    private $textValue;

	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Category", inversedBy="contents")
	  * @ORM\JoinColumn(nullable=false)
	 */
	private $category;
	
	
	public function __construct($label, $value, $type = "text")
	{
		$this->label = $label;
		$this->type = $type;
		$this->policyLevel = 1;
		$this->priority = 0;
		if(strcmp($type, 'textarea') === 0)
		{
			$this->textValue = $value;
		}
		else
		{
			$this->stringValue = $value;
		}
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
     * Set label
     *
     * @param string $label
     * @return Content
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
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
     * Set policyLevel
     *
     * @param integer $policyLevel
     * @return Content
     */
    public function setPolicyLevel($policyLevel)
    {
        $this->policyLevel = $policyLevel;

        return $this;
    }

    /**
     * Get policyLevel
     *
     * @return integer 
     */
    public function getPolicyLevel()
    {
        return $this->policyLevel;
    }

    /**
     * Set stringValue
     *
     * @param string $stringValue
     * @return Content
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    /**
     * Get stringValue
     *
     * @return string 
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }

    /**
     * Set textValue
     *
     * @param string $textValue
     * @return Content
     */
    public function setTextValue($textValue)
    {
        $this->textValue = $textValue;

        return $this;
    }

    /**
     * Get textValue
     *
     * @return string 
     */
    public function getTextValue()
    {
        return $this->textValue;
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
}
