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
	const DEFAULT_ISROOT = false;
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="isRoot", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $isRoot;
	
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
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Menu", mappedBy="module", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $menus;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Module", inversedBy="subModules")
	 */
	private $parentModule;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Module", mappedBy="parentModule", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $subModules;
	
	
	public function __construct($name)
    {
		parent::__construct();
		$this->name = $name;
		$this->isRoot = self::DEFAULT_ISROOT;
		$this->active = self::DEFAULT_ACTIVE;
		$this->priority = ++self::$number;
		$this->menus = new ArrayCollection();
    }
	
	/**
     * @ORM\PostPersist()
     */
    public function postPersist()
    {
        if($this->parentModule != null)
		{
			$this->parentModule->addSubModule($this);
		}
    }
	
	/**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        if($this->parentModule != null)
		{
			$this->parentModule->removeSubModule($this);
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
     * Set isRoot
     *
     * @param boolean $isRoot
     * @return Menu
     */
    public function setIsRoot($isRoot)
    {
        $this->isRoot = $isRoot;

        return $this;
    }

    /**
     * Get isRoot
     *
     * @return boolean 
     */
    public function getIsRoot()
    {
        return $this->isRoot;
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
	
	/**
     * Set parentModule
     *
     * @param \MyWebsite\WebBundle\Entity\Module $parentModule
     * @return Module
     */
    public function setParentModule(\MyWebsite\WebBundle\Entity\Module $parentModule = null)
    {
		if($this->parentModule != null)
		{
			if ($parentModule != null)
			{
				$this->parentModule->addSubModule($this);
			}
			else
			{
				$this->parentModule->removeSubModule($this);
			}
		}
		
		$this->parentModule = $parentModule;

        return $this;
    }

    /**
     * Get parentModule
     *
     * @return \MyWebsite\WebBundle\Entity\Module 
     */
    public function getParentModule()
    {
        return $this->parentModule;
    }

    /**
     * Add subModules
     *
     * @param \MyWebsite\WebBundle\Entity\Module $subModules
     * @return Module
     */
    public function addSubModule(\MyWebsite\WebBundle\Entity\Module $subModules)
    {
        $this->subModules[] = $subModules;

        return $this;
    }

    /**
     * Remove subModules
     *
     * @param \MyWebsite\WebBundle\Entity\Module $subModules
     */
    public function removeSubModule(\MyWebsite\WebBundle\Entity\Module $subModules)
    {
        $this->subModules->removeElement($subModules);
    }

    /**
     * Get subModules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubModules()
    {
        return $this->subModules;
    }
}
