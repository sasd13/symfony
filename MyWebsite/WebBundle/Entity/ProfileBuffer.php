<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\CategoryBuffer;

/**
 * ProfileBuffer
 */
class ProfileBuffer
{	
    /**
     * @var integer
	 *
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La priorité doit être au moins de 0"
     * )
     */
    private $id;
	
	private $categories;
	
	
	public function __construct($id)
	{
		$this->id = $id;
		$this->categories = new ArrayCollection();
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
     * Add categories
     *
     * @param \MyWebsite\WebBundle\Entity\CategoryBuffer $categories
     * @return ProfileBuffer
     */
    public function addCategory(\MyWebsite\WebBundle\Entity\CategoryBuffer $categories)
    {
		if(!$this->categories->contains($categories))
		{
			$this->categories[] = $categories;
		}

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \MyWebsite\WebBundle\Entity\CategoryBuffer $categories
     */
    public function removeCategory(\MyWebsite\WebBundle\Entity\CategoryBuffer $categories)
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
