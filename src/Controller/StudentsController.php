<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use primus852\ShortResponse\ShortResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function createOne(Request $request)
	{
		try {
			$service = new UserService($this->getDoctrine()->getManager(), $request);
		} catch (ApiException $e) {
			return ShortResponse::exception('Initialization failed, '.$e->getMessage());
		}
	
		try {
			$data = $service->create();
		} catch (ApiException $e) {
			return ShortResponse::exception('Query failed, please try again shortly ('.$e->getMessage().')');
		}
		
		return ShortResponse::success('created', $data);
	}	
}
