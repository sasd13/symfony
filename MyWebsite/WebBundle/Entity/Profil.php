<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\EditManager;
use MyWebsite\WebBundle\Entity\Category;
use MyWebsite\WebBundle\Entity\Content;

/**
 * Profil
 *
 * @ORM\Table(name="web_profil")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ProfilRepository")
 */
class Profil
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
     * @ORM\Column(name="firstName", type="string", length=45)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "2",
	 *		max = "45",
	 *		minMessage = "Le prénom doit faire plus de {{ limit }} caractères",
	 *		maxMessage = "Le prénom doit faire moins de {{ limit }} caractères"
	 * )
     */
    private $firstName;
	
	/**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=45)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "2",
	 *		max = "45",
	 *		minMessage = "Le nom doit faire plus de {{ limit }} caractères",
	 *		maxMessage = "Le nom doit faire moins de {{ limit }} caractères"
	 * )
     */
    private $lastName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pictureDisplay", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $pictureDisplay;

    /**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\EditManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $editManager;
	
	
	public function __construct($firstName, $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->pictureDisplay = true;
		$this->editManager = new EditManager();
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
     * @return Profil
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
     * @return Profil
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
     * Set pictureDisplay
     *
     * @param boolean $pictureDisplay
     * @return Profil
     */
    public function setPictureDisplay($pictureDisplay)
    {
        $this->pictureDisplay = $pictureDisplay;

        return $this;
    }

    /**
     * Get pictureDisplay
     *
     * @return boolean 
     */
    public function getPictureDisplay()
    {
        return $this->pictureDisplay;
    }
	
	/**
     * Set editManager
     *
     * @param \MyWebsite\WebBundle\Entity\EditManager $editManager
     * @return Profil
     */
    public function setEditManager(\MyWebsite\WebBundle\Entity\EditManager $editManager)
    {
        $this->editManager = $editManager;

        return $this;
    }

    /**
     * Get editManager
     *
     * @return \MyWebsite\WebBundle\Entity\EditManager 
     */
    public function getEditManager()
    {
        return $this->editManager;
    }
}
