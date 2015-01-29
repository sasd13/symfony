<?php

namespace MyWebsite\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\ProfileBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 *
 * @ORM\Table(name="profile_client")
 * @ORM\Entity(repositoryClass="MyWebsite\ProfileBundle\Entity\ClientRepository")
 */
class Client extends User
{
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
	 * @ORM\OneToMany(targetEntity="MyWebsite\CvBundle\Entity\Cv", mappedBy="client", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $cvs;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->cvs = new ArrayCollection();
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
	
	/**
     * Add cvs
     *
     * @param \MyWebsite\CvBundle\Entity\Cv $cvs
     * @return Client
     */
    public function addCv(\MyWebsite\CvBundle\Entity\Cv $cvs)
    {
		if(!$this->cvs->contains($cvs))
		{
			$this->cvs[] = $cvs;
		}

        return $this;
    }

    /**
     * Remove cvs
     *
     * @param \MyWebsite\CvBundle\Entity\Cv $cvs
     */
    public function removeCv(\MyWebsite\CvBundle\Entity\Cv $cvs)
    {
        $this->cvs->removeElement($cvs);
    }

    /**
     * Get cvs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCvs()
    {
        return $this->cvs;
    }
}
