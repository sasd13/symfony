<?php

namespace MyWebsite\WebBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Model\TimeManagerInterface;
use MyWebsite\WebBundle\Entity\TimeManager;

/**
 * AbstractModule
 *
 * @ORM\MappedSuperclass
 */
class AbstractEntity implements TimeManagerInterface
{
	/**
	 * @ORM\OneToOne(targetEntity="MyWebsite\WebBundle\Entity\TimeManager", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	protected $timeManager;
	
	
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
