<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\WebBundle\Entity\User;

/**
 * Admin
 *
 * @ORM\Table(name="web_admin")
 * @ORM\Entity(repositoryClass="MyWebsite\WebBundle\Entity\AdminRepository")
 */
class Admin extends User
{
	public function __construct()
	{
		parent::__construct();
	}
}
