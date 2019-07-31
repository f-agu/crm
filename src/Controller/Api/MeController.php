<?php

namespace App\Controller\Api;

use primus852\ShortResponse\ShortResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Model\MeView;
use Hateoas\HateoasBuilder;
use Symfony\Component\HttpFoundation\Response;
use App\Model\MeAnonymousView;
use OpenApi\Annotations as OA;

class MeController extends AbstractController
{
	
	/**
	 * @Route("/api/me", name="api_me_infos", methods={"GET"})
	 * @OA\Get(
	 *     path="/api/me",
	 *     summary="Gives informations about me",
	 *     @OA\Response(response="200", description="Successful")
	 * )
	 */
	public function infos()
	{
		$grantedRoles = array();
		if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
			array_push($grantedRoles, 'ROLE_SUPER_ADMIN');
		}
		if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
			array_push($grantedRoles, 'ROLE_ADMIN');
		}
		if($this->get('security.authorization_checker')->isGranted('ROLE_TEACHER')) {
			array_push($grantedRoles, 'ROLE_TEACHER');
		}
		if($this->get('security.authorization_checker')->isGranted('ROLE_STUDENT')) {
			array_push($grantedRoles, 'ROLE_STUDENT');
		}
		if($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
			array_push($grantedRoles, 'ROLE_USER');
		}
		if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
			array_push($grantedRoles, 'IS_AUTHENTICATED_ANONYMOUSLY');
		}
		
		$account = $this->getUser();
		if($account) {
			$me = new MeView($account, $account->getUser(), $grantedRoles);
		} else {
			$me = new MeAnonymousView($grantedRoles);
		}
		
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($me, 'json'));
			   
		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}
	
}
