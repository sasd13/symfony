<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;

/**
 * Administrator
 *
 * @ORM\Table(name="web_administrator")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\AdministratorRepository")
 */
class Administrator
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
     * @ORM\Column(name="login", type="string", length=45)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "4",
	 *		max = "45"
	 * )
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *		min = "4",
	 *		max = "45",
	 *		minMessage = "Le mot de passe doit faire plus de {{ limit }} caractères",
	 *		maxMessage = "Le mot de passe doit faire moins de {{ limit }} caractères"
	 * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email_backup", type="string", length=255, nullable=true)
	 * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide",
     *     checkMX = true
     * )
     */
    private $emailBackup;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $timeManager;
	
	
	public function __construct()
	{
		$this->timeManager = new TimeManager();
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
     * @return Administrator
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
     * @return Administrator
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
     * Set emailBackup
     *
     * @param string $emailBackup
     * @return Administrator
     */
    public function setEmailBackup($emailBackup)
    {
        $this->emailBackup = $emailBackup;

        return $this;
    }    

    /**
     * Get emailBackup
     *
     * @return string 
     */
    public function getEmailBackup()
    {
        return $this->emailBackup;
    }

    /**
     * Set timeManager
     *
     * @param \MyWebsite\WebBundle\Entity\TimeManager $timeManager
     * @return Administrator
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
