<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContentBuffer
 */
class ContentBuffer
{
    /**
     * @var integer
	 *
	 * @Assert\Range(
     *      min = 0,
     *      minMessage = "La priorité doit être au moins de 0"
     * )
     */
    private $id;
	
	/**
     * @var string
     *
     * @Assert\NotBlank
     */
    private $stringValue;
	
	/**
     * @var string
     *
     * @Assert\NotBlank
     */
    private $textValue;
	
	/**
     * @var boolean
	 *
     * @Assert\Type(type="bool")
     */
    private $required;
	
	/**
     * @var string
     *
     * @ORM\Column(name="formType", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $formType;
	
	
	public function __construct($id)
	{
		$this->id = $id;
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
     * Set stringValue
     *
     * @param string $stringValue
     * @return ContentBuffer
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
     * @return ContentBuffer
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
}
