<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;

/**
 * User
 *
 * @ORM\Table(name="web_user")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\UserRepository")
 */
class User
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
     * @ORM\Column(name="login", type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "4",
	 *		max = "50"
	 * )
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "4",
	 *		max = "50",
	 *		minMessage = "Le mot de passe doit faire plus de {{ limit }} caractères",
	 *		maxMessage = "Le mot de passe doit faire moins de {{ limit }} caractères"
	 * )
     */
    private $password;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="privacyLevel", type="smallint")
	 * @Assert\Range(
     *      min = 1,
	 *      max = 3,
     *      minMessage = "La priorité doit être plus de {{ limit }}",
	 *      maxMessage = "La priorité doit être moins de {{ limit }}"
     * )
     */
    private $privacyLevel;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $timeManager;
	
	
	public function __construct()
	{
		$this->privacyLevel = 1;
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
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set privacyLevel
     *
     * @param integer $privacyLevel
     * @return User
     */
    public function setPrivacyLevel($privacyLevel)
    {
        $this->privacyLevel = $privacyLevel;

        return $this;
    }

    /**
     * Get privacyLevel
     *
     * @return integer 
     */
    public function getPrivacyLevel()
    {
        return $this->privacyLevel;
    }

    /**
     * Set timeManager
     *
     * @param \MyWebsite\WebBundle\Entity\TimeManager $timeManager
     * @return User
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
}
