<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;
use \DateTime;

/**
 * BundleManager
 *
 * @ORM\Table(name="web_bundlemanager")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\BundleManagerRepository")
 */
class BundleManager
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
     * @ORM\Column(name="bundleName", type="string", length=255)
     * @Assert\NotBlank
     */
    public $bundleName;

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
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $timeManager;
	
	
	public function __construct($bundleName, $active = true) 
	{
		$this->bundleName = $bundleName;
		$this->active = $active;
		$this->timeManager = new TimeManager();
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
     * Set bundleName
     *
     * @param string $bundleName
     * @return BundleManager
     */
    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;

        return $this;
    }

    /**
     * Get bundleName
     *
     * @return string 
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return BundleManager
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
     * @return BundleManager
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
     * @return BundleManager
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
