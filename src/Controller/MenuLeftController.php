<?php

namespace App\Controller;

use App\Entity\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuLeftController extends AbstractController
{

	public function viewMenuLeft($club)
	{
		$menuItems = $this->getDoctrine()->getManager()
				->getRepository(MenuItem::class)
				->findBy([], ['priority' => 'ASC']);
		
		// filter
		$filteredMenuItems = array();
		foreach($menuItems as $menuItem) {
			foreach($menuItem->getAvailableForRoles() as $role) {
				if($this->get('security.authorization_checker')->isGranted($role)) {
					array_push($filteredMenuItems, $menuItem);
					break;
				}
			}
		}
		
		return $this->render(
			'menuleft.html.twig',
			['menuItems' => $filteredMenuItems, 'club' => $club]
		);
	}
}
