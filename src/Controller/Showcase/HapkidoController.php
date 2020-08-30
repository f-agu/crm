<?php

namespace App\Controller\Showcase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HapkidoController extends AbstractController
{
	/**
	* @Route("/hapkido", name="hapkido")
	*/
	public function viewHapkido(): Response
	{
		$user = $this->getUser();
		return $this->render('showcase/hapkido.html.twig', [
			'connectedUser' => $user
		]);
	}
}
