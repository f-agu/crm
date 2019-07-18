<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture
{
	private $passwordEncoder;


	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function load(ObjectManager $manager)
	{
		$user = $this->createUser('admin', 'admin', '11/07/2019');
		$manager->persist($user);

		$account = $this->createAccount($user, 'admin', 'admin');
		$manager->persist($account);

		$manager->persist($this->createUser('u1', 'u1', '11/07/2019'));
		$manager->persist($this->createUser('u2', 'u2', '11/07/2019'));
		$manager->persist($this->createUser('u3', 'u3', '11/07/2019'));
		$manager->persist($this->createUser('u4', 'u4', '11/07/2019'));

		$manager->flush();
	}

	private function createUser($lastname, $firstname, $birthday)
	{
		$user = new User();
		$user->setLastname($lastname);
		$user->setFirstname($firstname);
		$user->setBirthday(new \DateTime($birthday));
		return $user;
	}

	private function createAccount($user, $login, $password)
	{
		$account = new Account();
		$account->setUser($user);
		$account->setLogin($login);
		$account->setPassword($this->passwordEncoder->encodePassword($account, $password));        
		return $account;
	}

}
