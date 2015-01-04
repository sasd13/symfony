<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\Category;
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
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Category", mappedBy="profile", cascade={"remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $categories;
	
	
	public function __construct($firstName, $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
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
