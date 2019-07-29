<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	* @Route("/", name="home")
	*/
	public function viewHome(): Response
	{
		$user = $this->getUser();
		return $this->render('home.html.twig', [
			'connectedUser' => $user
		]);
	}
}
