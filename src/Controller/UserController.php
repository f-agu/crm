<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user", methods={"GET"})
     * @IsGranted("ROLE_TEACHER")
     */
    public function viewAll(Request $request, LoggerInterface $logger)
    {
        $user = $this->getUser();
        $response = $this->forward('App\Controller\Api\UserController::listAll', array('request'  => $request));
        $json = json_decode($response->getContent());
        return $this->render('users.html.twig', [
            'user' => $user,
            'users' => $json->extra
        ]);
    }
  
}
