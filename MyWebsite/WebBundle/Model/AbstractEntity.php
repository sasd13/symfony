<?php

namespace MyWebsite\WebBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Model\CopyInterface;

/**
 * AbstractEntity
 */
class AbstractEntity implements CopyInterface
{
	/**
     * @var boolean
     *
     * @Assert\Type(type="integer")
     */
    protected $idCopy;
	
	
	public function setIdCopy($idCopy)
	{
		$this->idCopy = $idCopy;
		
		return $this;
	}
	
	public function getIdCopy()
	{
		return $this->idCopy;
	}
}
