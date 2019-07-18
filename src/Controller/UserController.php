<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
	/**
	* @Route("/user", name="create_user")
	*/
	public function createUser(ValidatorInterface $validator): Response
	{
		$entityManager = $this->getDoctrine()->getManager();

		$user = new User();
		$user->setLastname('Doe');
		$user->setFirstname('John');

		$errors = $validator->validate($user);
		if (count($errors) > 0) {
		return new Response((string) $errors, 400);
		}

		// tell Doctrine you want to (eventually) save the user (no queries yet)
		$entityManager->persist($user);

		// actually executes the queries (i.e. the INSERT query)
		$entityManager->flush();

		return new Response('Saved new user with id '.$user->getId());
	}
}
