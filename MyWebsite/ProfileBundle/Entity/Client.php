<?php

namespace MyWebsite\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\ProfileBundle\Entity\User;
use MyWebsite\WebBundle\Model\CopyInterface;

/**
 * Client
 *
 * @ORM\Table(name="profile_client")
 * @ORM\Entity(repositoryClass="MyWebsite\ProfileBundle\Entity\ClientRepository")
 */
class Client extends User implements CopyInterface
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
	 * @ORM\Column(name="pictureTitle", type="string", length=255, nullable=true)
     */
    private $pictureTitle;
	
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
		$client = new Client();
		$client
			->setIdCopy($this->getId())
			->setEmail($this->getEmail())
			->setFirstName($this->firstName)
			->setLastName($this->lastName)
			->setPictureTitle($this->pictureTitle)
			->setPicturePath($this->picturePath)
		;
		foreach($this->getCategories() as $category)
		{
			$client->addCategory($category->copy());
		}
		
		return $client;
	}
	
	/**
     * Set firstName
     *
     * @param string $firstName
     * @return Client
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
     * @return Client
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
     * Set pictureTitle
     *
     * @param string $pictureTitle
     * @return Client
     */
    public function setPictureTitle($pictureTitle)
    {
        $this->pictureTitle = $pictureTitle;

        return $this;
    }

    /**
     * Get pictureTitle
     *
     * @return string 
     */
    public function getPictureTitle()
    {
        return $this->pictureTitle;
    }

    /**
     * Set picturePath
     *
     * @param string $picturePath
     * @return Client
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
