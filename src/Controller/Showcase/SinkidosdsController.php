<?php

namespace App\Controller\Showcase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SinkidosdsController extends AbstractController
{
	/**
	* @Route("/sinkidosds", name="sinkidosds")
	*/
	public function viewSinkidosds(): Response
	{
		$user = $this->getUser();
		return $this->render('showcase/sinkidosds.html.twig', [
			'connectedUser' => $user
		]);
	}
}
