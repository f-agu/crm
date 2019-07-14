<?php

namespace App\DataFixtures;

use App\Entity\MenuItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MenuItemFixtures extends Fixture
{
    
	public function load(ObjectManager $manager)
	{
		$manager->persist($this->createItem("clubs", 100));
		$manager->persist($this->createItem("documents", 200));
		$manager->flush();
	}
    
    private function createItem($code, $priority)
    {
    	$item = new MenuItem();
    	$item->setCode($code);
    	$item->setPriority($priority);
    	return $item;
    }
}
