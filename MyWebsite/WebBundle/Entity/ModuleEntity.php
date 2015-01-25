<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ModuleEntity
 *
 * @ORM\Table(name="web_moduleentity")
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class ModuleEntity extends AbstractEntity
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
	 * @ORM\OneToMany(targetEntity="MyWebsite\WebBundle\Entity\Category", mappedBy="moduleEntity", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $categories;
	
	
	public function __construct()
	{
		parent::__construct();
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
     * @param \MyWebsite\WebBundle\Entity\Category $categories
     * @return Profile
     */
    public function addCategory(\MyWebsite\WebBundle\Entity\Category $categories)
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
     * @param \MyWebsite\WebBundle\Entity\Category $categories
     */
    public function removeCategory(\MyWebsite\WebBundle\Entity\Category $categories)
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
