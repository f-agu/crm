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
		return $this->render('users.html.twig', [
			'connectedUser' => $user,
			'users' => $json->users
		]);
	}

	/**
	 * @Route("/user/me/pwd/request", name="web_user_me_password_request_view", methods={"GET"})
	 */
	public function requestPassword(LoggerInterface $logger)
	{
		$user = $this->getUser();
		if( ! $user) {
			$logger->info('RequestPassword not available for authenticated user: '.$user->getId());
			return new Response('', 403);
		}

		return $this->render('forgot-password.html.twig');
	}

		/**
	 * @Route("/user/me/pwd/request", name="web_user_me_password_request_send", methods={"POST"})
	 */
	public function requestPassword(Request $request, LoggerInterface $logger)
	{
		$user = $this->getUser();
		if( ! $user) {
			$logger->info('RequestPassword not available for authenticated user: '.$user->getId());
			return new Response('', 403);
		}

		$csrf = $request->request->get('_csrf_token');
		$token = new CsrfToken('authenticate', $csrf);
		if( ! $this->csrfTokenManager->isTokenValid($token)) {
			$logger->info('RequestPassword failed by CSRF token');
			return new Response('', 403);
		}

		$login = $request->request->get('login');
		if(strlen($login) < 4) {
			$logger->info('RequestPassword failed by login too small: '.$login);
			return new Response('', 403);
		}


		$request = $this->getDoctrine()->getManager()->getRepository(AccountPasswordRequest::class)
		->buildByValidLogin($login);

		//$response = $this->forward('App\Controller\Api\UserController::one', ['uuid' => $uuid]);
		//$json = json_decode('');
		return new Response($request->getUuid(), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}

	/**
	 * @Route("/user/{uuid}", name="web_user_one", methods={"GET"})
	 * @IsGranted("ROLE_CLUB_MANAGER")
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
