<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\ModuleEntity;

/**
 * User
 *
 * @ORM\Table(name="web_user")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\ProfileRepository")
 */
class User extends ModuleEntity
{
	const PRIVACYLEVEL_LOW = 1;
	const PRIVACYLEVEL_MEDIUM = 2;
	const PRIVACYLEVEL_HIGH = 3;
	
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
	
	
	public function __construct()
	{
		parent::__construct();
		$this->privacyLevel = self::PRIVACYLEVEL_LOW;
	}
	
	/**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
		//Control before persist
		//Throw Exception
    }
	
	/**
	 * @ORM\PostPersist()
     */
    public function postPersist()
    {
        //Control after persist
		//Throw Exception
    }
	
	/**
	 * @ORM\PreRemove()
     */
    public function preRemove()
    {
        //Control before remove
		//Throw Exception
    }
	
	/**
     * Set login
     *
     * @param string $login
     * @return AbstractUser
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
     * @return AbstractUser
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
     * Set privacyLevel
     *
     * @param integer $privacyLevel
     * @return AbstractUser
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
}
