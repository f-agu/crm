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
	    $user = $this->getUser();
	    
	    if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
	        $students = $this->getDoctrine()->getManager()
	           ->getRepository(User::class)
	           ->findBy([], [
	              'lastname' => 'ASC',
	              'firstname' => 'ASC'
	           ]);
	    } elseif($this->get('security.authorization_checker')->isGranted("ROLE_TEACHER")) {
	        $students = $this->getDoctrine()->getManager()
	           ->getRepository(User::class)
	           ->findInMyClubs($user->getId());
	    }
		
		return $this->render('students.html.twig', [
			'user' => $user,
            'students' => $students
		]);
	}

}
