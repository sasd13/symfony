<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Model\LifeCycleInterface;

/**
 * Menu
 *
 * @ORM\Table(name="web_menu")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\MenuRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Menu implements LifeCycleInterface
{
	const DEFAULT_ISROOT = false;
	const DEFAULT_ACTIVE = true;
	
	const DISPLAY_PUBLIC_ONLY = 1;
	const DISPLAY_CONFIG_ONLY = 2;
	
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=255, nullable=true)
     */
    private $target;

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
     * @ORM\Column(name="display", type="smallint")
	 * @Assert\Range(
     *      min = 1,
     *      max = 3,
     * )
     */
    private $display;

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
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Menu", inversedBy="subMenus")
	 */
	private $parentMenu;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Menu", mappedBy="parentMenu", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $subMenus;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Module", inversedBy="menus")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $module;
	
	
	public function __construct($name, $target)
    {
		$this->name = $name;
		$this->target = $target;
		$this->isRoot = self::DEFAULT_ISROOT;
		$this->active = self::DEFAULT_ACTIVE;
		$this->display = self::DISPLAY_PUBLIC_ONLY;
		$this->priority = ++self::$number;
    }
	
	/**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->module->addMenu($this);
		if($this->parentMenu != null)
		{
			$this->parentMenu->addSubMenu($this);
		}
		
		//Control before persist
		//Throw Exception
    }
	
	/**
     * @ORM\PostPersist()
     */
    public function postPersist()
    {
        $this->module->addMenu($this);
		if($this->parentMenu != null)
		{
			$this->parentMenu->addSubMenu($this);
		}
    }
	
	/**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->module->removeMenu($this);
		if($this->parentMenu != null)
		{
			$this->parentMenu->removeSubMenu($this);
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
     * Set display
     *
     * @param integer $display
     * @return Menu
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return integer 
     */
    public function getDisplay()
    {
        return $this->display;
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
     * Set parentMenu
     *
     * @param \MyWebsite\WebBundle\Entity\Menu $parentMenu
     * @return Menu
     */
    public function setParentMenu(\MyWebsite\WebBundle\Entity\Menu $parentMenu = null)
    {
		if($this->parentMenu != null)
		{
			if ($parentMenu != null)
			{
				$this->parentMenu->addSubMenu($this);
			}
			else
			{
				$this->parentMenu->removeSubMenu($this);
			}
		}
		
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
