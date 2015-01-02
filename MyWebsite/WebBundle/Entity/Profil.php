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
     * @var boolean
     *
     * @ORM\Column(name="pictureDisplay", type="boolean")
     */
    private $pictureDisplay;

    /**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\EditManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $editManager;
	
	
	public function __construct()
	{
		$this->pictureDisplay = true;
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
