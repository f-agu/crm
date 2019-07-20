<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StudentsController extends AbstractController
{
	/**
 	 * @Route("/students", name="students", methods={"GET"})
	 * @IsGranted("ROLE_TEACHER")
	 */
	public function viewAll()
	{
		$students = $this->getDoctrine()->getManager()
				->getRepository(User::class)
				->findBy([], ['lastname' => 'ASC']);
		
		// TODO filter
		
		$user = $this->getUser();
		return $this->render('students.html.twig', [
			'user' => $user,
		  'students' => $students
		]);
	}
	
	/**
 	 * @Route("/student", name="student-create-one", methods={"POST"})
	 * @IsGranted("ROLE_TEACHER")
	 */
	public function createOne()
	{
		$students = $this->getDoctrine()->getManager()
				->getRepository(User::class)
				->findBy([], ['lastname' => 'ASC']);
		
		// TODO filter
		
		$user = $this->getUser();
		return $this->render('students.html.twig', [
			'user' => $user,
		  'students' => $students
		]);
	}	
}
