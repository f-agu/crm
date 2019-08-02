<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
	/**
	 * @Route("/user", name="web_user_all", methods={"GET"})
	 * @IsGranted("ROLE_TEACHER")
	 */
	public function viewAll(Request $request)
	{
		$user = $this->getUser();
		$response = $this->forward('App\Controller\Api\UserController::listAll', ['request' => $request]);
		$json = json_decode($response->getContent());
		return $this->render('users.html.twig', [
			'connectedUser' => $user,
			'users' => $json->users
		]);
	}

	/**
	 * @Route("/user/{uuid}", name="web_user_one", methods={"GET"})
	 * @IsGranted("ROLE_TEACHER")
	 */
	public function viewOne($uuid)
	{
		$user = $this->getUser();
		$response = $this->forward('App\Controller\Api\UserController::one', ['uuid' => $uuid]);
		$json = json_decode($response->getContent());
		return $this->render('user.html.twig', [
			'connectedUser' => $user,
			'user' => $json->user
		]);
	}

}
