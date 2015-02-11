<?php

namespace MyWebsite\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyWebsite\ProfileBundle\Entity\User;

/**
 * Admin
 *
 * @ORM\Table(name="profile_admin")
 * @ORM\Entity(repositoryClass="MyWebsite\ProfileBundle\Entity\AdminRepository")
 */
class Admin extends User
{
	public function __construct()
	{
		parent::__construct();
	}
}
