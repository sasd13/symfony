<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Admin;
use MyWebsite\WebBundle\Entity\MyTime;
use \DateTime;

class AdminFixtures implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$admin = new Admin();
		$admin->setLogin("root");
		$admin->setPassword(null);
		$admin->setEmailBackup(null);
		
		$myTime = new MyTime();
		$myTime->setCreateTime(new DateTime());
		$myTime->setUpdateTime(null);
		
		$admin->setMyTime($myTime);
		
		$manager->persist($admin);
		$manager->flush();
	}
}