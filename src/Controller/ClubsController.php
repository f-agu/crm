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
		/*return $this->forward('App\Controller\DashboardController::dashboard', [
			'blockName' => 'clublist',
			'templateName' => 'clubs.html.twig'
		]);*/
		$clubDTOs = $this->getDoctrine()->getManager()
				->getRepository(Club::class)
				->findAllActiveGroupedWithCities();
				//->findBy([], ['name' => 'ASC']);
				
		$user = $this->getUser();
		return $this->render('clubs.html.twig', [
			'user' => $user,
		  'clubs' => $clubDTOs
		]);
	}
}
