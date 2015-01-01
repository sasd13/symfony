<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=45)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBirth", type="date", nullable=true)
     */
    private $dateBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=45, nullable=true)
     */
    private $city;
	
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\Country", cascade={"persist"})
	 */
	private $country;
	
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
     * Set dateBirth
     *
     * @param \DateTime $dateBirth
     * @return Profil
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return \DateTime 
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Profil
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return Profil
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Profil
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param \MyWebsite\WebBundle\Entity\Country $country
     * @return Profil
     */
    public function setCountry(\MyWebsite\WebBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \MyWebsite\WebBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set myTime
     *
     * @param \MyWebsite\WebBundle\Entity\MyTime $myTime
     * @return Profil
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
