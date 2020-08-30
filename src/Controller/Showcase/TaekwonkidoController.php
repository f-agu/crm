<?php

namespace App\Controller\Showcase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaekwonkidoController extends AbstractController
{
	/**
	* @Route("/taekwonkido", name="Taekwonkido")
	*/
	public function viewTaekwonkido(): Response
	{
		$user = $this->getUser();
		return $this->render('showcase/taekwonkido.html.twig', [
			'connectedUser' => $user
		]);
	}
}
