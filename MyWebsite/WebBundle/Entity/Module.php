<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Model\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Module
 *
 * @ORM\Table(name="web_module")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ModuleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Module extends AbstractEntity
{
	const DEFAULT_ACTIVE = true;
	
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $name;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $active;
	
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
     * @ORM\Column(name="readMe", type="text", nullable=true)
     */
    private $readMe;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Bundle", inversedBy="modules")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $bundle;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Menu", mappedBy="module", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $menus;
	
	
	public function __construct($name)
    {
		parent::__construct();
		$this->name = $name;
		$this->active = self::DEFAULT_ACTIVE;
		$this->priority = ++self::$number;
		$this->menus = new ArrayCollection();
    }
	
	/**
	 * @ORM\PostPersist()
     */
    public function postPersist()
    {
        $this->bundle->addModule($this);
    }
	
	/**
	 * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->bundle->removeModule($this);
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
     * @return Module
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
     * Set active
     *
     * @param boolean $active
     * @return Module
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
     * Set priority
     *
     * @param integer $priority
     * @return Module
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
     * Set readMe
     *
     * @param string $readMe
     * @return Module
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
     * Set bundle
     *
     * @param \MyWebsite\WebBundle\Entity\Bundle $bundle
     * @return Menu
     */
    public function setBundle(\MyWebsite\WebBundle\Entity\Bundle $bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * Get bundle
     *
     * @return \MyWebsite\WebBundle\Entity\Bundle 
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Add menus
     *
     * @param \MyWebsite\WebBundle\Entity\Menu $menus
     * @return Module
     */
    public function addMenu(\MyWebsite\WebBundle\Entity\Menu $menus)
    {
		if(!$this->menus->contains($menus))
		{
			$this->menus[] = $menus;
		}

        return $this;
    }

    /**
     * Remove menus
     *
     * @param \MyWebsite\WebBundle\Entity\Menu $menus
     */
    public function removeMenu(\MyWebsite\WebBundle\Entity\Menu $menus)
    {
        $this->menus->removeElement($menus);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }
}
