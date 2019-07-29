<?php

namespace App\Controller;

use App\Entity\Club;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClubsController extends AbstractController
{
	/**
 	* @Route("/clubs", name="clubs")
	*/
	public function index()
	{
		$clubDTOs = $this->getDoctrine()->getManager()
				->getRepository(Club::class)
				->findAllActiveGroupedWithCities();
				//->findBy([], ['name' => 'ASC']);
				
		$user = $this->getUser();
		return $this->render('clubs.html.twig', [
			'connectedUser' => $user,
		    'clubs' => $clubDTOs
		]);
	}
}
