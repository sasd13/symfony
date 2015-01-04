<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EditManager
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;
	
	/**
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
     * @ORM\Column(name="pictureDisplay", type="string", length=255, nullable=true)
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
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Category", inversedBy="documents", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $category;
	
	
	public function __construct($name, $mimeType, $path)
	{
		$this->name = $name;
		$this->mimeType = $mimeType;
		$this->display = true;
		$this->path = $path;
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
