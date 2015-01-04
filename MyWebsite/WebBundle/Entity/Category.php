<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;

/**
 * Category
 *
 * @ORM\Table(name="web_category")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="title", type="string", length=45, unique=true)
	 * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=45, unique=true)
	 * @Assert\NotBlank
     */
    private $tag;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $timeManager;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\BundleManager")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $bundleManager;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Content", mappedBy="category", cascade={"remove"})
	 */
	private $contents;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Document", mappedBy="category", cascade={"remove"})
	 */
	private $documents;
	
	
	public function __construct($title, $tag)
	{
		$this->title = $title;
		$this->tag = $tag;
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
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return Category
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }
	
	/**
     * Set timeManager
     *
     * @param \MyWebsite\WebBundle\Entity\TimeManager $timeManager
     * @return Profil
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

    /**
     * Set bundleManager
     *
     * @param \MyWebsite\WebBundle\Entity\BundleManager $bundleManager
     * @return Category
     */
    public function setBundleManager(\MyWebsite\WebBundle\Entity\BundleManager $bundleManager)
    {
        $this->bundleManager = $bundleManager;

        return $this;
    }

    /**
     * Get bundleManager
     *
     * @return \MyWebsite\WebBundle\Entity\BundleManager 
     */
    public function getBundleManager()
    {
        return $this->bundleManager;
    }

    /**
     * Add contents
     *
     * @param \MyWebsite\WebBundle\Entity\Content $contents
     * @return Category
     */
    public function addContent(\MyWebsite\WebBundle\Entity\Content $contents)
    {
        $this->contents[] = $contents;

        return $this;
    }

    /**
     * Remove contents
     *
     * @param \MyWebsite\WebBundle\Entity\Content $contents
     */
    public function removeContent(\MyWebsite\WebBundle\Entity\Content $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Add documents
     *
     * @param \MyWebsite\WebBundle\Entity\Document $documents
     * @return Category
     */
    public function addDocument(\MyWebsite\WebBundle\Entity\Document $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param \MyWebsite\WebBundle\Entity\Document $documents
     */
    public function removeDocument(\MyWebsite\WebBundle\Entity\Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
