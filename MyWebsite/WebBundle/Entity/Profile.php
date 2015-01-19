<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Model\AbstractUser;
use MyWebsite\WebBundle\Model\CopyInterface;
use MyWebsite\WebBundle\Entity\Category;

/**
 * Profile
 *
 * @ORM\Table(name="web_profile")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ProfileRepository")
 */
class Profile extends AbstractUser implements CopyInterface
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
     * @ORM\Column(name="firstName", type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "2",
	 *		max = "50",
	 *		minMessage = "Le prénom doit faire plus de {{ limit }} caractères",
	 *		maxMessage = "Le prénom doit faire moins de {{ limit }} caractères"
	 * )
     */
    private $firstName;
	
	/**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "2",
	 *		max = "50",
	 *		minMessage = "Le nom doit faire plus de {{ limit }} caractères",
	 *		maxMessage = "Le nom doit faire moins de {{ limit }} caractères"
	 * )
     */
    private $lastName;
	
	/**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
	 * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide",
     *     checkMX = true
     * )
     */
    private $email;
	
	/**
     * @var string
	 *
	 * @ORM\Column(name="pictureName", type="string", length=255, nullable=true)
     */
    private $pictureName;
	
	/**
     * @var string
	 *
	 * @ORM\Column(name="picturePath", type="string", length=255, nullable=true)
     */
    private $picturePath;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Category", mappedBy="profile", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $categories;
	
	/**
     * @var boolean
     *
     * @Assert\Type(type="integer")
     */
    private $idCopy;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->categories = new ArrayCollection();
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
		$profile = new Profile();
		$profile
			->setIdCopy($this->id)
			->setFirstName($this->firstName)
			->setLastName($this->lastName)
			->setEmail($this->email)
			->setPictureName($this->pictureName)
			->setPicturePath($this->picturePath)
		;
		foreach($this->categories as $category)
		{
			$profile->addCategory($category->copy());
		}
		
		return $profile;
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
     * Set firstName
     *
     * @param string $firstName
     * @return Profile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Profile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Profile
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
	
	/**
     * Set pictureName
     *
     * @param string $pictureName
     * @return Profile
     */
    public function setPictureName($pictureName)
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    /**
     * Get pictureName
     *
     * @return string 
     */
    public function getPictureName()
    {
        return $this->pictureName;
    }

    /**
     * Set picturePath
     *
     * @param string $picturePath
     * @return Profile
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    /**
     * Get picturePath
     *
     * @return string 
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * Add categories
     *
     * @param \MyWebsite\WebBundle\Entity\Category $categories
     * @return Profile
     */
    public function addCategory(\MyWebsite\WebBundle\Entity\Category $categories)
    {
		if(!$this->categories->contains($categories))
		{
			$this->categories[] = $categories;
		}

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \MyWebsite\WebBundle\Entity\Category $categories
     */
    public function removeCategory(\MyWebsite\WebBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
