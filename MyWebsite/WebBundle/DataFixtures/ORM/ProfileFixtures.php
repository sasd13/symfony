<?php

namespace MyWebsite\WebBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyWebsite\WebBundle\Entity\User;
use MyWebsite\WebBundle\Entity\Profile;

class ProfileFixtures extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$timeManager = $this->getReference('timeManager4');
		$user = $this->getReference('user');
		
		$profile = new Profile("your first name", "your last name", "exemple@email.com");
		$profile->setTimeManager($timeManager);
		$profile->setUser($user);
		
		$manager->persist($profile);
		$manager->flush();
		
		$this->addReference('profile', $profile);
	}
	
	public function getOrder()
    {
        return 4; // l'ordre dans lequel les fichiers sont chargés
    }
}