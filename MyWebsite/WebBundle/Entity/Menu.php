<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="web_menu")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=255)
     */
    private $target;

    /**
     * @var string
     *
     * @ORM\Column(name="configName", type="string", length=255)
     */
    private $configName;

    /**
     * @var string
     *
     * @ORM\Column(name="configTarget", type="string", length=255)
     */
    private $configTarget;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="smallint")
     */
    private $priority;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Menu", inversedBy="subMenus")
	 */
	private $parentMenu;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Menu", mappedBy="parentMenu", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $subMenus;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\Module", inversedBy="menu")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $module;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
		$this->subMenus = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Menu
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
     * @return Menu
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
     * Set configName
     *
     * @param string $configName
     * @return Menu
     */
    public function setConfigName($configName)
    {
        $this->configName = $configName;

        return $this;
    }

    /**
     * Get configName
     *
     * @return string 
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * Set configTarget
     *
     * @param string $configTarget
     * @return Menu
     */
    public function setConfigTarget($configTarget)
    {
        $this->configTarget = $configTarget;

        return $this;
    }

    /**
     * Get configTarget
     *
     * @return string 
     */
    public function getConfigTarget()
    {
        return $this->configTarget;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Menu
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
     * @return Menu
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
     * Constructor
     */
    public function __construct()
    {
        $this->subMenus = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set parentMenu
     *
     * @param \MyWebsite\WebBundle\Entity\Menu $parentMenu
     * @return Menu
     */
    public function setParentMenu(\MyWebsite\WebBundle\Entity\Menu $parentMenu)
    {
        $this->parentMenu = $parentMenu;

        return $this;
    }

    /**
     * Get parentMenu
     *
     * @return \MyWebsite\WebBundle\Entity\Menu 
     */
    public function getParentMenu()
    {
        return $this->parentMenu;
    }

    /**
     * Add subMenus
     *
     * @param \MyWebsite\WebBundle\Entity\Menu $subMenus
     * @return Menu
     */
    public function addSubMenu(\MyWebsite\WebBundle\Entity\Menu $subMenus)
    {
        $this->subMenus[] = $subMenus;

        return $this;
    }

    /**
     * Remove subMenus
     *
     * @param \MyWebsite\WebBundle\Entity\Menu $subMenus
     */
    public function removeSubMenu(\MyWebsite\WebBundle\Entity\Menu $subMenus)
    {
        $this->subMenus->removeElement($subMenus);
    }

    /**
     * Get subMenus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubMenus()
    {
        return $this->subMenus;
    }

    /**
     * Set module
     *
     * @param \MyWebsite\WebBundle\Entity\Module $module
     * @return Menu
     */
    public function setModule(\MyWebsite\WebBundle\Entity\Module $module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \MyWebsite\WebBundle\Entity\Module 
     */
    public function getModule()
    {
        return $this->module;
    }
}
