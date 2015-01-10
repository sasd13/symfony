<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use MyWebsite\WebBundle\Entity\ContentBuffer;

/**
 * CategoryBuffer
 */
class CategoryBuffer
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

	private $contents;
	
	
	public function __construct($id)
	{
		$this->id = $id;
		$this->contents = new ArrayCollection();
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
     * Add contents
     *
     * @param \MyWebsite\WebBundle\Entity\ContentBuffer $contents
     * @return CategoryBuffer
     */
    public function addContent(\MyWebsite\WebBundle\Entity\ContentBuffer $contents)
    {
		if(!$this->contents->contains($contents))
		{
			$this->contents[] = $contents;
		}

        return $this;
    }

    /**
     * Remove contents
     *
     * @param \MyWebsite\WebBundle\Entity\ContentBuffer $contents
     */
    public function removeContent(\MyWebsite\WebBundle\Entity\ContentBuffer $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContents()
    {
        return $this->contents;
    }
}
