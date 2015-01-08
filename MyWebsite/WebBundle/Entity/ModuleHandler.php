<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;
use \DateTime;

/**
 * ModuleHandler
 *
 * @ORM\Table(name="web_modulehandler")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ModuleHandlerRepository")
 */
class ModuleHandler
{
	private static $number = 0;
	
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;
	
	/**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $target;
	
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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $active;
	
	/**
     * @var string
     *
     * @ORM\Column(name="readMe", type="text", nullable=true)
     */
    private $readMe;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $timeManager;
	
	
	public function __construct()
    {
		self::$number++;
		$this->priority = self::$number;
		$this->active = true;
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
     * Set name
     *
     * @param string $name
     * @return ModuleHandler
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
     * Set target
     *
     * @param string $target
     * @return ModuleHandler
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return string 
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return ModuleHandler
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
     * Set active
     *
     * @param boolean $active
     * @return ModuleHandler
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
     * Set readMe
     *
     * @param string $readMe
     * @return ModuleHandler
     */
    public function setReadMe($readMe)
    {
        $this->readMe = $readMe;

        return $this;
    }

    /**
     * Get readMe
     *
     * @return string 
     */
    public function getReadMe()
    {
        return $this->readMe;
    }

    /**
     * Set timeManager
     *
     * @param \MyWebsite\WebBundle\Entity\TimeManager $timeManager
     * @return ModuleHandler
     */
    public function setTimeManager(\MyWebsite\WebBundle\Entity\TimeManager $timeManager)
    {
        $this->timeManager = $timeManager;

        return $this;
    }

    /**
     * Get timeManager
     *
     * @return \MyWebsite\WebBundle\Entity\TimeManager 
     */
    public function getTimeManager()
    {
        return $this->timeManager;
    }
}
