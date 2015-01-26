<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\TimeManager;
use MyWebsite\WebBundle\Model\TimeManagerInterface;

/**
 * AbstractEntity
 *
 * @ORM\MappedSuperclass
 */
class AbstractEntity implements TimeManagerInterface
{
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $timeManager;
	
	
	public function __construct()
	{
		$this->timeManager = new TimeManager();
	}
	
	public function getCreatedAt()
	{
		return $this->timeManager->getCreatedAt();
	}
	
	public function getUpdatedAt()
	{
		return $this->timeManager->getUpdatedAt();
	}
	
	public function update()
	{
		$this->timeManager->update();
	}
}
