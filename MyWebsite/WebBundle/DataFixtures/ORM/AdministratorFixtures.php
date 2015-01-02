<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\Administrator;
use MyWebsite\WebBundle\Entity\EditManager;
use \DateTime;

class AdminFixtures implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$admin = new Administrator();
		$admin->setLogin('root');
		$admin->setPassword('root');
		$admin->setEmailBackup(null);
		
		$editManager = new EditManager();
		$editManager->setCreateTime(new DateTime());
		$editManager->setUpdateTime(null);
		
		$admin->setEditManager($editManager);
		
		$manager->persist($admin);
		$manager->flush();
	}
}