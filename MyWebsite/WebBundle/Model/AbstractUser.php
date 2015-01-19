<?php

namespace MyWebsite\WebBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Model\LifeCycleInterface;
use MyWebsite\WebBundle\Model\TimeManagerInterface;
use MyWebsite\WebBundle\Entity\TimeManager;

/**
 * AbstractUser
 *
 * @ORM\MappedSuperclass
 */
class AbstractUser implements TimeManagerInterface, LifeCycleInterface
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
    protected $login;

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
    protected $password;
	
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
    protected $privacyLevel;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	protected $timeManager;
	
	
	public function __construct()
	{
		$this->privacyLevel = self::PRIVACYLEVEL_LOW;
		$this->timeManager = new TimeManager();
	}
	
	public function getCreatedAt()
	{
		return $this->timeManager->getCreatedAt();
	}
	
	public function getUpdatedAt()
	{
		return $this->timeManager->getUpdatedAt();
	}
	
	public function update()
	{
		$this->timeManager->update();
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
