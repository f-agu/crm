<?php

namespace App\Controller\Showcase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CenacleController extends AbstractController
{
	/**
	* @Route("/cenacle", name="cenacle")
	*/
	public function viewCenacle(): Response
	{
		$user = $this->getUser();
		return $this->render('showcase/cenacle.html.twig', [
			'connectedUser' => $user
		]);
	}
}
