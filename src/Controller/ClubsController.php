<?php

namespace App\Controller;

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
		$user = $this->getUser();
		return $this->render('clubs.html.twig', [
			'user' => $user,
		  'controller_name' => 'ClubsController',
		]);
	}
}
