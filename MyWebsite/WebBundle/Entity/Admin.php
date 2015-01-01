<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table(name="web_admin")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\AdminRepository")
 */
class Admin
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
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email_backup", type="string", length=255, nullable=true)
     */
    private $emailBackup;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\MyTime", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $myTime;


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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * Set myTime
     *
     * @param \MyWebsite\WebBundle\Entity\MyTime $myTime
     * @return Admin
     */
    public function setMyTime(\MyWebsite\WebBundle\Entity\MyTime $myTime)
    {
        $this->myTime = $myTime;

        return $this;
    }

    /**
     * Get myTime
     *
     * @return \MyWebsite\WebBundle\Entity\MyTime 
     */
    public function getMyTime()
    {
        return $this->myTime;
    }    
}
