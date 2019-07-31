<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
	/**
 	 * @Route("/club", name="web_club_list-active", methods={"GET"})
	 */
	public function listActive()
	{
		$user = $this->getUser();
		$response = $this->forward('App\Controller\Api\ClubController::listActive');
		$json = json_decode($response->getContent());
		return $this->render('clubs.html.twig', [
			'connectedUser' => $user,
			'clubs' => $json->clubs
		]);
	}

	/**
	 * @Route("/club/{uuid}", name="web_club_one", methods={"GET"})
	 */
	public function viewOne($uuid)
	{
		$user = $this->getUser();
		$response = $this->forward('App\Controller\Api\ClubController::one', ['uuid' => $uuid]);
		$json = json_decode($response->getContent());
		return $this->render('club.html.twig', [
			'connectedUser' => $user,
			'club' => $json->club
		]);
	}

}
