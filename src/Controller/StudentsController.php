<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\UserCreate;
use App\Service\UserService;
use App\Util\RequestUtil;
use primus852\ShortResponse\ShortResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Exception\ViolationException;
use Symfony\Component\HttpFoundation\Response;

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
	public function createOne(Request $request, SerializerInterface $serializer, TranslatorInterface $translator)
	{
	    $requestUtil = new RequestUtil($serializer, $translator);
	   
	    try {
	       $userCreate = $requestUtil->validate($request, UserCreate::class);
	    } catch (ViolationException $e) {
	        return ShortResponse::error("data", $e->getErrors())
	           ->setStatusCode(Response::HTTP_BAD_REQUEST);
	    }
	    
		try {
			$service = new UserService($this->getDoctrine()->getManager(), $request);
		} catch (\Exception $e) {
			return ShortResponse::exception('Initialization failed, '.$e->getMessage());
		}
	
		try {
		    $data = $service->create($userCreate);
		} catch (\Exception $e) {
			return ShortResponse::exception('Query failed, please try again shortly ('.$e->getMessage().')');
		}
		
		/*$data =  array(
		    'lastname' => $userCreate->getLastname(),
		    'firstname' => $userCreate->getFirstname()
		);*/
		
		
		return ShortResponse::success('created', $data);
	}	
}
