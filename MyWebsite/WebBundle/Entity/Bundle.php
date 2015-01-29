<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\AbstractTimedEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Bundle
 *
 * @ORM\Table(name="web_bundle")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\BundleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Bundle extends AbstractTimedEntity
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
     * @var string
     *
     * @ORM\Column(name="readMe", type="text", nullable=true)
     */
    private $readMe;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Module", mappedBy="bundle", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $modules;
	
	
	public function __construct($name)
    {
		parent::__construct();
		$this->name = $name;
		$this->active = self::DEFAULT_ACTIVE;
		$this->modules = new ArrayCollection();
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
     * @return Bundle
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
     * @return Bundle
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
     * @return Bundle
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
     * Add modules
     *
     * @param \MyWebsite\WebBundle\Entity\Module $modules
     * @return Bundle
     */
    public function addModule(\MyWebsite\WebBundle\Entity\Module $modules)
    {
		if(!$this->modules->contains($modules))
		{
			$this->modules[] = $modules;
		}

        return $this;
    }

    /**
     * Remove modules
     *
     * @param \MyWebsite\WebBundle\Entity\Module $modules
     */
    public function removeModule(\MyWebsite\WebBundle\Entity\Module $modules)
    {
        $this->modules->removeElement($modules);
    }

    /**
     * Get modules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModules()
    {
        return $this->modules;
    }
}
