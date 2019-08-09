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
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Security\PasswordValidator;
use App\Exception\ViolationException;

class UserPasswordController extends AbstractController
{
	private $logger;
	private $translator;
	
	public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
	{
		$this->logger = $logger;
		$this->translator = $translator;
	}
	
	/**
	 * @Route("/user/pwd/request", name="web_user_password_request_view", methods={"GET"})
	 */
	public function requestPasswordView()
	{
		/*$user = $this->getUser();
		if($user != null) {
			$this->logger->info('RequestPassword not available for authenticated user: '.$user->getId());
			return new Response('', 403);
		}*/
		return $this->render('security/forgot-password.html.twig');
	}

	/**
	 * @Route("/user/pwd/request", name="web_user_password_request_send", methods={"POST"})
	 */
	public function requestPasswordSend(Request $request, CsrfTokenManagerInterface $csrfTokenManager, \Swift_Mailer $mailer)
	{
		/*$user = $this->getUser();
		if($user != null) {
			$this->logger->info('RequestPassword: not available for authenticated user: '.$user->getId());
			return new Response('', Response::HTTP_UNAUTHORIZED);
		}*/

		$csrf = $request->request->get('_csrf_token');
		$token = new CsrfToken('forgot-password', $csrf);
		if( ! $csrfTokenManager->isTokenValid($token)) {
			$this->logger->info('RequestPassword: failed by CSRF token');
			return new Response('', Response::HTTP_BAD_REQUEST);
		}

		$login = $request->request->get('login');
		if(strlen($login) < 1) { // TODO defined min length for login
			$this->logger->info('RequestPassword: failed by login too small: '.$login);
			return new Response('', Response::HTTP_BAD_REQUEST);
		}

		$request = $this->getDoctrine()->getManager()->getRepository(AccountPasswordRequest::class)->buildByValidLogin($login);

		if($request != null) {
			$this->logger->info('RequestPassword: generate or retrieve for "'.$login.'" : '.$request->getId());
			$user = $this->getDoctrine()->getManager()->getRepository(User::class)
				->find($request->getAccount()->getUser()->getId());
			if(! empty($user->getMails())) {
				// AccountPasswordRequest
				$mailFactory = new EmailFactory($mailer, $this->translator, $this->container->get('twig'), $logger);
				$mailFactory->buildAndSend(
					new PasswordRequestEmailParameters(),
					$user->getMails(),
					[
						'requestUuid' => $request->getUuid(),
						'login' => $request->getAccount()->getLogin(),
						'name' => $user->getLastname().' '.$user->getFirstname()
					]);
			} else {
				$this->logger->info('RequestPassword: failed because no emails defined for userId: '.$user->getId());
			}

		} else {
			$this->logger->info('RequestPassword: login not found or not opened: '.$login);
		}
		return $this->render('security/forgot-password-sent.html.twig');
	}

	/**
	 * @Route("/user/pwd/renewal/{uuid}", name="web_user_me_password_renewal-view", methods={"GET"}, requirements={"uuid"="[a-zA-Z0-9]{30,50}"})
	 */
	public function requestPasswordRenawalView(Request $request, $uuid)
	{
		// don't check uuid to avoid giving information
		return $this->render('security/forgot-password-renewal.html.twig');
	}

	/**
	 * @Route("/user/pwd/renewal/{uuid}", name="web_user_me_password_renewal-update", methods={"POST"}, requirements={"uuid"="[a-zA-Z0-9]{30,50}"})
	 */
	public function requestPasswordRenawalUpdate(Request $request, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, $uuid)
	{
		$this->logger->debug('Renewaling password for request '.$uuid);
		
		$csrf = $request->request->get('_csrf_token');
		$token = new CsrfToken('password-renewal', $csrf);
		if( ! $csrfTokenManager->isTokenValid($token)) {
			$this->logger->info('RenewalPassword: failed by CSRF token');
			return new Response('', Response::HTTP_BAD_REQUEST);
		}
		
		$pwdrequest = $this->getDoctrine()->getManager()
			->getRepository(AccountPasswordRequest::class)
			->findBy(['uuid' => $uuid]);
		if(empty($request)) {
			$this->logger->debug('Renewaling password for request '.$uuid.' : not found');
			return new Response('', Response::HTTP_NOT_FOUND);
		}
		$pwdrequest = $pwdrequest[0];
		
		$pwd1 = trim($request->request->get('password', ''));
		$pwd2 = trim($request->request->get('password2', ''));
		if(empty($pwd1)) {
			$this->logger->debug('Renewaling password for request '.$uuid.' : password empty');
			return $this->render('security/forgot-password-renewal.html.twig',
				['error' => $this->translator->trans('Password is empty')]);
		}
		
		try {
			(new PasswordValidator($this->translator))->validate($pwd1);
		} catch (ViolationException $e) {
			$this->logger->debug('Renewaling password for request '.$uuid.' : password not valid', ['errors' => $e->getErrors()]);
			return $this->render('security/forgot-password-renewal.html.twig',
				[
					'error' => $this->translator->trans('Passwords not valid'),
					'errorpolicies' => $e->getErrors()
				]);
		}
		
		if($pwd1 !== $pwd2) {
			$this->logger->debug('Renewaling password for request '.$uuid.' : password not equals');
			return $this->render('security/forgot-password-renewal.html.twig',
				['error' => $this->translator->trans('Passwords are not equals')]);
		}
		
		$account = $pwdrequest->getAccount();
		$account->setPassword($passwordEncoder->encodePassword($account, $pwd1));
		$this->getDoctrine()->getManager()->remove($pwdrequest);
		$this->getDoctrine()->getManager()->flush();
		
		$this->logger->debug('Renewal password done for request '.$uuid.', accountId: '.$account->getId());
				
		return $this->render('security/forgot-password-renewal-updated.html.twig');
	}
	
}
