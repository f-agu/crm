<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Psr\Log\LoggerInterface;
use App\Entity\AccountPasswordRequest;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class UserController extends AbstractController
{
	/**
	 * @Route("/user", name="web_user_all", methods={"GET"})
	 * @IsGranted("ROLE_CLUB_MANAGER")
	 */
	public function viewAll(Request $request)
	{
		$user = $this->getUser();
		$response = $this->forward('App\Controller\Api\UserController::listAll', ['request' => $request]);
		$json = json_decode($response->getContent());
		return $this->render('user/users.html.twig', [
			'connectedUser' => $user,
			'users' => $json->users
		]);
	}

	/**
	 * @Route("/user/{uuid}", name="web_user_one", methods={"GET"})
	 * @IsGranted("ROLE_CLUB_MANAGER")
	 */
	public function viewOne($uuid)
	{
		$user = $this->getUser();
		$response = $this->forward('App\Controller\Api\UserUuidController::one', ['uuid' => $uuid]);
		$json = json_decode($response->getContent());
		return $this->render('user/user.html.twig', [
			'connectedUser' => $user,
			'user' => $json->user
		]);
	}

}
