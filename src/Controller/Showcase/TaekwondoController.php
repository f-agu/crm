<?php

namespace App\Controller\Showcase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaekwondoController extends AbstractController
{
	/**
	* @Route("/taekwondo", name="taekwondo")
	*/
	public function viewTaekwondo(): Response
	{
		$user = $this->getUser();
		return $this->render('showcase/taekwondo.html.twig', [
			'connectedUser' => $user
		]);
	}
}
