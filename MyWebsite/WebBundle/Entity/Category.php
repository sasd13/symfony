<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Model\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Model\CopyInterface;

/**
 * Category
 *
 * @ORM\Table(name="web_category")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category extends AbstractEntity implements CopyInterface
{
	const TAG_PROFILE_INFO = 'profile_info';
	const TAG_PROFILE_CONTACT = 'profile_contact';
	const TAG_PROFILE_PICTURE = 'profile_picture';
	
	const TITLE_PROFILE_INFO = 'Identité';
	const TITLE_PROFILE_CONTACT = 'Contact';
	const TITLE_PROFILE_PICTURE = 'Photo de profil';
	
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
     * @ORM\Column(name="title", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $tag;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
	 * @Assert\NotBlank
     */
    private $type;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Content", mappedBy="category", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $contents;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Document", mappedBy="category", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $documents;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\ModuleEntity", inversedBy="categories")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $moduleEntity;
	
	/**
     * @var boolean
     *
     * @Assert\Type(type="integer")
     */
    private $idCopy;
	
	
	public function __construct($type = 'content')
	{
		parent::__construct();
		$this->type = $type;
		$this->contents = new ArrayCollection();
		$this->documents = new ArrayCollection();
	}
	
	/**
     * @ORM\PrePersist()
     */
    private function prePersist()
    {
		//Control before persist
		//Throw Exception
    }
	
	/**
	 * @ORM\PostPersist()
     */
    private function postPersist()
    {
        $this->moduleEntity->addCategory($this);
    }
	
	/**
	 * @ORM\PreRemove()
     */
    private function preRemove()
    {
        $this->moduleEntity->removeCategory($this);
    }
	
	public function setIdCopy($idCopy)
	{
		$this->idCopy = $idCopy;
		
		return $this;
	}
	
	public function getIdCopy()
	{
		return $this->idCopy;
	}
	
	public function copy()
	{
		$category = new Category($this->type);
		$category
			->setIdCopy($this->id)
			->setTitle($this->title)
			->setTag($this->tag)
			->setModuleEntity($this->moduleEntity)
		;
		foreach($this->contents as $content)
		{
			$category->addContent($content->copy());
		}
		foreach($this->documents as $document)
		{
			$category->addDocument($document->copy());
		}
		
		return $category;
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
     * Set type
     *
     * @param string $type
     * @return Category
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add contents
     *
     * @param \MyWebsite\WebBundle\Entity\Content $contents
     * @return Category
     */
    public function addContent(\MyWebsite\WebBundle\Entity\Content $contents)
    {
		if(!$this->contents->contains($contents))
		{
			$this->contents[] = $contents;
		}

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
		if(!$this->documents->contains($documents))
		{
			$this->documents[] = $documents;
		}

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

    /**
     * Set moduleEntity
     *
     * @param \MyWebsite\WebBundle\Entity\ModuleEntity $moduleEntity
     * @return Category
     */
    public function setModuleEntity(\MyWebsite\WebBundle\Entity\ModuleEntity $moduleEntity)
    {
        $this->moduleEntity = $moduleEntity;

        return $this;
    }

    /**
     * Get moduleEntity
     *
     * @return \MyWebsite\WebBundle\Entity\ModuleEntity 
     */
    public function getModuleEntity()
    {
        return $this->moduleEntity;
    }
}
