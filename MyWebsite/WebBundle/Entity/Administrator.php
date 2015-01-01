<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\EditManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $editManager;


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
     * Set editManager
     *
     * @param \MyWebsite\WebBundle\Entity\EditManager $editManager
     * @return Administrator
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
