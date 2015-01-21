<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Model\CopyInterface;

/**
 * Profile
 *
 * @ORM\Table(name="web_profile")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ProfileRepository")
 */
class Profile extends User implements CopyInterface
{
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
     * @var boolean
     *
     * @Assert\Type(type="integer")
     */
    private $idCopy;
	
	
	public function __construct()
	{
		parent::__construct();
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
			->setIdCopy($this->getId())
			->setEmail($this->getEmail())
			->setFirstName($this->firstName)
			->setLastName($this->lastName)
			->setPictureName($this->pictureName)
			->setPicturePath($this->picturePath)
		;
		foreach($this->getCategories() as $category)
		{
			$profile->addCategory($category->copy());
		}
		
		return $profile;
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
}
