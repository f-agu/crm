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
		$this->persistUserAccount($manager, 'admin nom', 'admin prenom', '11/07/2019', 'admin', 'admin', ['ROLE_ADMIN']);
		$this->persistUserAccount($manager, 'prof1 nom', 'prof1 prenom', '11/07/2019', 'prof1', 'prof1', ['ROLE_TEACHER']);
		$this->persistUserAccount($manager, 'prof2 nom', 'prof2 prenom', '11/07/2019', 'prof2', 'prof2', ['ROLE_TEACHER', 'ROLE_STUDENT']);
		$this->persistUserAccount($manager, 'eleve1 nom', 'eleve1 prenom', '11/07/2019', 'eleve1', 'eleve1', ['ROLE_STUDENT']);
		$this->persistUserAccount($manager, 'eleve2 nom', 'eleve2 prenom', '11/07/2019', 'eleve2', 'eleve2', ['ROLE_STUDENT']);
		$this->persistUserAccount($manager, 'eleve3 nom', 'eleve3 prenom', '11/07/2019', 'eleve3', 'eleve3', ['ROLE_STUDENT']);
		$this->persistUserAccount($manager, 'eleve4 nom', 'eleve4 prenom', '11/07/2019', 'eleve4', 'eleve4', ['ROLE_STUDENT']);

		$manager->persist($this->createUser('u1', 'u1', '11/07/2019'));
		$manager->persist($this->createUser('u2', 'u2', '11/07/2019'));
		$manager->persist($this->createUser('u3', 'u3', '11/07/2019'));
		$manager->persist($this->createUser('u4', 'u4', '11/07/2019'));

		$manager->flush();
	}
	
	private function persistUserAccount(ObjectManager $manager, $lastname, $firstname, $birthday, $login, $password, $roles)
	{
		$user = $this->createUser($lastname, $firstname, $birthday);
		$manager->persist($user);
		$manager->persist($this->createAccount($user, $login, $password, $roles));
	}
	

	private function createUser($lastname, $firstname, $birthday)
	{
		$user = new User();
		$user->setLastname($lastname);
		$user->setFirstname($firstname);
		$user->setBirthday(new \DateTime($birthday));
		return $user;
	}

	private function createAccount($user, $login, $password, $roles)
	{
		$account = new Account();
		$account->setUser($user);
		$account->setLogin($login);
		$account->setPassword($this->passwordEncoder->encodePassword($account, $password));        
		$account->setRoles($roles);
		return $account;
	}

}
