<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use MyWebsite\WebBundle\Model\DocumentInterface;
use \DateTime;

/**
 * Document
 *
 * @ORM\Table(name="web_document")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document implements DocumentInterface
{
	const DEFAULT_MIMETYPE = 'text/plain';
	const DEFAULT_PATH = 'path';
	const DEFAULT_HIDE = false;
	
	const SUBDIR_DOCUMENT = 'documents';
	const SUBDIR_IMAGE = 'images';
	
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
     * @ORM\Column(name="mimeType", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $mimeType;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="hide", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $hide;

    /**
     * @var string
	 *
     * @ORM\Column(name="path", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $path;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="uploadDate", type="datetime")
	 * @Assert\Type(type="\DateTime")
     */
    private $uploadDate;
	
	/**
     * @Assert\File(
     *     maxSize = "5120k",
	 *     maxSizeMessage = "Fichier trop volumineux"
     * )
     */
    public $file;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Category", inversedBy="documents")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $category;
	
	
	public function __construct($type = 'document')
    {
		if($type === 'image')
		{
			$this->mimeType = 'image/png';
		}
		else
		{
			$this->mimeType = self::DEFAULT_MIMETYPE;
		}
		$this->path = self::DEFAULT_PATH;
		$this->hide = self::DEFAULT_HIDE;
		$this->uploadDate = new DateTime();
    }
	
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/bundles/mywebsiteweb/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche le document dans la vue.
		if(strpos($this->mimeType, 'image') >= 0)
		{
			return 'uploads/'.self::SUBDIR_IMAGE;
		}
		else
		{
			return 'uploads/'.self::SUBDIR_DOCUMENT;
		}
    }
	
	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    protected function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)).'_'.$this->file->getClientOriginalName();
			
			$this->mimeType = $this->file->getMimeType();
			$this->uploadDate = new DateTime();
        }
    }

	 /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    protected function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
		
		$this->category->addDocument($this);
    }
	
	/**
     * @ORM\PreRemove()
     */
    protected function preRemove()
    {
        $this->category->removeDocument($this);
    }

    /**
     * @ORM\PostRemove()
     */
    protected function removeUpload()
    {
        if (is_file($this->getAbsolutePath())) {
			unlink($this->getAbsolutePath());
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
     * Set hide
     *
     * @param boolean $hide
     * @return Document
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Get hide
     *
     * @return boolean 
     */
    public function getHide()
    {
        return $this->hide;
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
     * Get uploadDate
     *
     * @return \DateTime 
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
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
