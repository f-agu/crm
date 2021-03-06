<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\AccountSessionHistory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
	use TargetPathTrait;

	private $entityManager;
	private $urlGenerator;
	private $csrfTokenManager;
	private $passwordEncoder;
	private $logger;

	public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger)
	{
		$this->entityManager = $entityManager;
		$this->urlGenerator = $urlGenerator;
		$this->csrfTokenManager = $csrfTokenManager;
		$this->passwordEncoder = $passwordEncoder;
		$this->logger = $logger;
	}

	public function supports(Request $request)
	{
		return 'app_login' === $request->attributes->get('_route')
			&& $request->isMethod('POST');
	}

	public function getCredentials(Request $request)
	{
		$credentials = [
			'login' => $request->request->get('login'),
			'password' => $request->request->get('password'),
			'csrf_token' => $request->request->get('_csrf_token'),
		];
		$request->getSession()->set(
			Security::LAST_USERNAME,
			$credentials['login']
		);

		return $credentials;
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		$token = new CsrfToken('authenticate', $credentials['csrf_token']);
		if (!$this->csrfTokenManager->isTokenValid($token)) {
			throw new InvalidCsrfTokenException();
		}

		$user = $this->entityManager
			->getRepository(Account::class)
			->findOneBy([
				'login' => $credentials['login'],
				'has_access' => true
			]);

		if (!$user) {
			// fail authentication with a custom error
			throw new CustomUserMessageAuthenticationException('Login could not be found.');
		}

		return $user;
	}

	public function checkCredentials($credentials, UserInterface $user)
	{
		$pwd = $user->getPassword();
		if(substr($pwd, 0, 5) === 'sha1:') {
			return $this->checkCredentialsLegacy(substr($pwd, 5), $credentials, $user);
		}
		return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
			return new RedirectResponse($targetPath);
		}

		$sessionHst = new AccountSessionHistory();
		$sessionHst->setAccount($token->getUser());
		$sessionHst->setIp($request->getClientIp());
		$sessionHst->setUserAgent($request->headers->get('User-Agent'));
		$this->entityManager->persist($sessionHst);
		$this->entityManager->flush();

		// For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
		// redirect to some "app_homepage" route - of wherever you want
		return new RedirectResponse($this->urlGenerator->generate('home'));
		//throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
	}

	//********************************************

	protected function getLoginUrl()
	{
		return $this->urlGenerator->generate('app_login');
	}

	//********************************************

	private function checkCredentialsLegacy($sha1, $credentials, Account $user)
	{
		$salt = 'gh(-#fgbVD56ù@iutyxc +tyu_75^rrtyè6';
		$input = sha1($credentials['password'].$salt);
		if($input === $sha1) {
			$this->logger->info('Upgrade legacy password for user '.$user->getId());
			$newpwd = $this->passwordEncoder->encodePassword($user, $credentials['password']);
			$this->logger->info('newpwd '.$newpwd);
			$user->setPassword($newpwd);
			$user = $this->entityManager->flush();
			return true;
		}
		$newpwd = $this->passwordEncoder->encodePassword($user, $credentials['password']);
		$this->logger->info('Bad legacy password for user '.$user->getId());
		$this->logger->info('newpwd2: '.$newpwd);
		return false;
	}
}
