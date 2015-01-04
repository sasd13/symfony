<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;
use \DateTime;

/**
 * Document
 *
 * @ORM\Table(name="web_document")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\DocumentRepository")
 */
class Document
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
	 *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;
	
	/**
     * @var string
	 *
     * @ORM\Column(name="mimeType", type="string", length=255)
     * @Assert\NotBlank
     */
    public $mimeType;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="display", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $display;

    /**
     * @var string
	 *
     * @ORM\Column(name="path", type="string", length=255)
     * @Assert\NotBlank
     */
    public $path;
	
	/**
     * @Assert\File(
     *     maxSize = "5120k",
     *     mimeTypes = {"image/gif", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Choisissez un fichier JPEG, PNG ou GIF valide",
	 *     maxSizeMessage = "Fichier trop volumineux"
     * )
     */
    public $file;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="timeManager_id", referencedColumnName="id", nullable=false)
	 */
	private $timeManager;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Category", inversedBy="documents", cascade={"persist"})
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
	 */
	private $category;
	
	
	public function __construct()
    {
		$this->display = true;
		$this->timeManager = new TimeManager();
    }
	
	//Fonction de réinitialisation des documents
	public function setDefault($type)
    {
		//Photos
		if(strcmp($type, "image") === 0)
		{
			$this->name = "Photo";
			$this->mimeType = "image/gif";
			$this->display = true;
			$this->path = "images/inconnu.gif";
			$this->timeManager->setUpdateTime(new DateTime());
		}
		
		return $this;
    }
	
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche le document dans la vue.
        return 'uploads';
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
     * @return Document
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
     * Set mimeType
     *
     * @param string $mimeType
     * @return Document
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set display
     *
     * @param boolean $display
     * @return Document
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return boolean 
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set timeManager
     *
     * @param \MyWebsite\WebBundle\Entity\TimeManager $timeManager
     * @return Document
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
     * Set category
     *
     * @param \MyWebsite\WebBundle\Entity\Category $category
     * @return Document
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
