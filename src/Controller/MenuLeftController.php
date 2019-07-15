<?php

namespace App\Controller;

use App\Entity\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuLeftController extends AbstractController
{

	public function viewMenuLeft()
	{
		$menuItems = $this->getDoctrine()->getManager()
				->getRepository(MenuItem::class)
				->findAll([], ['priority' => 'ASC']);
		// TODO filter with roles
		return $this->render(
				'menuleft.html.twig',
				['menuItems' => $menuItems]
			);
	}
}
