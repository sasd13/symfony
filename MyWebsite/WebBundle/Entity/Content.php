<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Content
 *
 * @ORM\Table(name="web_content")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ContentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Content
{
	const LABEL_PROFILE_FIRSTNAME = 'profile_first_name';
	const LABEL_PROFILE_LASTNAME = 'profile_last_name';
	const LABEL_PROFILE_EMAIL = 'profile_email';
	
	const LABELVALUE_PROFILE_FIRSTNAME = 'First name';
	const LABELVALUE_PROFILE_LASTNAME = 'Last name';
	const LABELVALUE_PROFILE_EMAIL = 'Email';
	
	const POLICYLEVEL_LOW = 1;
	const POLICYLEVEL_MEDIUM = 2;
	const POLICYLEVEL_HIGH = 3;
	
	const DEFAULT_REQUIRED = false;
	
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
     * @ORM\Column(name="labelValue", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $labelValue;
	
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
     * @var string
     *
     * @ORM\Column(name="formType", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $formType;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="required", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $required;
	
	/**
     * @var boolean
     *
     * @Assert\Type(type="bool")
     */
    private $contextChanged;
	
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
     * @var string
     *
     * @ORM\Column(name="placeholder", type="string", length=255, nullable=true)
     */
    private $placeholder;

	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Category", inversedBy="contents")
	  * @ORM\JoinColumn(nullable=false)
	 */
	private $category;
	
	
	public function __construct($label, $formType = 'text')
	{
		$this->label = strtolower($label);
		$this->formType = $formType;
		$this->required = self::DEFAULT_REQUIRED;
		$this->contextChanged = false;
		$this->policyLevel = self::POLICYLEVEL_MEDIUM;
		$this->priority = 0;
	}
	
	/**
     * @ORM\PostPersist()
     */
    protected function postPersist()
    {
        $this->category->addContent($this);
    }
	
	/**
     * @ORM\PreRemove()
     */
    protected function preRemove()
    {
        $this->category->removeContent($this);
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
        $this->label = strtolower($label);

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
     * Set labelValue
     *
     * @param string $labelValue
     * @return Content
     */
    public function setLabelValue($labelValue)
    {
        $this->labelValue = $labelValue;

        return $this;
    }

    /**
     * Get labelValue
     *
     * @return string 
     */
    public function getLabelValue()
    {
        return $this->labelValue;
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
     * Set formType
     *
     * @param string $formType
     * @return Content
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * Get formType
     *
     * @return string 
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return Content
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean 
     */
    public function getRequired()
    {
        return $this->required;
    }
	
	/**
     * Set contextChanged
     *
     * @param boolean $contextChanged
     * @return TimeManager
     */
    public function setContextChanged($contextChanged)
    {
        $this->contextChanged = $contextChanged;

        return $this;
    }

    /**
     * Get contextChanged
     *
     * @return boolean 
     */
    public function getContextChanged()
    {
        return $this->contextChanged;
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
     * Set placeholder
     *
     * @param string $placeholder
     * @return Content
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder
     *
     * @return string 
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
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
