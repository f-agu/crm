<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Psr\Log\LoggerInterface;
use App\Entity\AccountPasswordRequest;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Emails\EmailFactory;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Emails\PasswordRequestEmailParameters;

class UserPasswordController extends AbstractController
{

	/**
	 * @Route("/user/pwd/request", name="web_user_password_request_view", methods={"GET"})
	 */
	public function requestPasswordView(LoggerInterface $logger)
	{
		$obj = $this->container->get('twig');
		var_dump(get_class($obj));
		var_dump($obj);

		/*$user = $this->getUser();
		if($user != null) {
			$logger->info('RequestPassword not available for authenticated user: '.$user->getId());
			return new Response('', 403);
		}*/
		return $this->render('security/forgot-password.html.twig');
	}

	/**
	 * @Route("/user/pwd/request", name="web_user_password_request_send", methods={"POST"})
	 */
	public function requestPasswordSend(Request $request, LoggerInterface $logger, CsrfTokenManagerInterface $csrfTokenManager, \Swift_Mailer $mailer, TranslatorInterface $translator)
	{
		/*$user = $this->getUser();
		if($user != null) {
			$logger->info('RequestPassword: not available for authenticated user: '.$user->getId());
			return new Response('', Response::HTTP_UNAUTHORIZED);
		}*/

		$csrf = $request->request->get('_csrf_token');
		$token = new CsrfToken('forgot-password', $csrf);
		if( ! $csrfTokenManager->isTokenValid($token)) {
			$logger->info('RequestPassword: failed by CSRF token');
			return new Response('', Response::HTTP_BAD_REQUEST);
		}

		$login = $request->request->get('login');
		if(strlen($login) < 1) { // TODO defined min length for login
			$logger->info('RequestPassword: failed by login too small: '.$login);
			return new Response('', Response::HTTP_BAD_REQUEST);
		}

		$request = $this->getDoctrine()->getManager()->getRepository(AccountPasswordRequest::class)->buildByValidLogin($login);

		if($request != null) {
			$logger->info('RequestPassword: generate for: '.$login);
			// AccountPasswordRequest
			$mailFactory = new EmailFactory($mailer, $translator, $this->container->get('twig'), $logger);
// TODO
			$mailFactory->buildAndSend(
				new PasswordRequestEmailParameters(),
				'toto@domain.org',
				[
					'requestUuid' => $request->getUuid(),
					'name' => 'TODO'
				]);

		} else {
			$logger->info('RequestPassword: login not found or not opened: '.$login);
		}
		return $this->render('security/forgot-password-sent.html.twig');
	}

	/**
	 * @Route("/user/pwd/renewal/{uuid}", name="web_user_me_password_renewal", methods={"GET"}, requirements={"uuid"="[a-zA-Z0-9]{30,50}"})
	 */
	public function requestPasswordRenawal(Request $request, LoggerInterface $logger, $uuid)
	{

	}

}
