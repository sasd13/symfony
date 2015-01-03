<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MyValue
 *
 * @ORM\Table(name="web_myvalue")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\MyValueRepository")
 */
class MyValue
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
     * @var integer
     *
     * @ORM\Column(name="level", type="smallint")
	 * @Assert\Range(
     *      min = 1,
	 *      max = 3,
     *      minMessage = "La priorité doit être plus de {{ limit }}",
	 *      maxMessage = "La priorité doit être moins de {{ limit }}"
     * )
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="stringValue", type="string", length=255)
     */
    private $stringValue;

    /**
	 * @ORM\ManyToOne(targetEntity="MyWebsite\WebBundle\Entity\Content")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $content;


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
     * Set level
     *
     * @param integer $level
     * @return MyValue
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set stringValue
     *
     * @param string $stringValue
     * @return MyValue
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    /**
     * Get stringValue
     *
     * @return string 
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }
	
	/**
     * Set content
     *
     * @param \MyWebsite\WebBundle\Entity\Content $content
     * @return MyValue
     */
    public function setContent(\MyWebsite\WebBundle\Entity\Content $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \MyWebsite\WebBundle\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
    }
}
