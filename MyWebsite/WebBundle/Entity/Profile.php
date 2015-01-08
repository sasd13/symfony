<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Document;
use MyWebsite\WebBundle\Entity\TimeManager;

/**
 * Profile
 *
 * @ORM\Table(name="web_profile")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ProfileRepository")
 */
class Profile
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
     * @ORM\Column(name="picturePath", type="string", length=255)
     * @Assert\NotBlank
     */
    private $picturePath;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $timeManager;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\User", cascade={"remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $user;
	
	/**
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Category", mappedBy="profile", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $categories;
	
	
	public function __construct()
	{
		$document = new Document();
		$this->picturePath = $document->setDefault('profile_picture')->getPath();
		$this->categories = new ArrayCollection();
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
     * Set timeManager
     *
     * @param \MyWebsite\WebBundle\Entity\TimeManager $timeManager
     * @return Profile
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
     * Set user
     *
     * @param \MyWebsite\WebBundle\Entity\User $user
     * @return Profile
     */
    public function setUser(\MyWebsite\WebBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MyWebsite\WebBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add categories
     *
     * @param \MyWebsite\WebBundle\Entity\Category $categories
     * @return Profile
     */
    public function addCategory(\MyWebsite\WebBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

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
