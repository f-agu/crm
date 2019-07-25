<?php

namespace App\DataFixtures;

use App\Entity\MenuItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MenuItemFixtures extends Fixture
{
    
	public function load(ObjectManager $manager)
	{
		$manager->persist($this->createItem("clubs", 100, ["IS_AUTHENTICATED_ANONYMOUSLY"]));
		$manager->persist($this->createItem("documents", 200, ["ROLE_TEACHER", "ROLE_STUDENT"]));
		$manager->persist($this->createItem("students", 300, ["ROLE_TEACHER"]));
		$manager->persist($this->createItem("teachers", 400, ["ROLE_ADMIN"]));
		
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
