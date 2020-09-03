<?php

namespace App\DataFixtures;

use App\Entity\MenuItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MenuItemFixtures extends Fixture
{

	public function load(ObjectManager $manager)
	{

		// for a club
		$manager->persist($this->createItem("pres", 10, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("hours", 20, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("price", 30, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("location", 40, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("contact", 50, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		
		// global
		$manager->persist($this->createItem("cenacle", 100, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("master", 110, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("clubs", 120, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("sport", 150, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("documents", 200, ["ROLE_TEACHER", "ROLE_STUDENT"]));
		$manager->persist($this->createItem("users", 300, ["ROLE_TEACHER"]));

		$manager->flush();
	}

	private function createItem($code, $priority, $roles = [])
	{
		$item = new MenuItem();
		$item->setCode($code);
		$item->setPriority($priority);
		$item->setAvailableForRoles($roles);
		return $item;
	}
}
