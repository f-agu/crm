<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\ViolationException;
use App\Model\UserCreate;
use App\Model\UserView;
use App\Service\UserService;
use App\Util\RequestUtil;
use Hateoas\HateoasBuilder;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use primus852\ShortResponse\ShortResponse;
use OpenApi\Annotations as OA;
use App\Model\UsersView;
use App\Model\Pagination;
use App\Util\Pager;
use App\Model\UserUpdate;
use App\Model\UserMeView;
use App\Model\MeAnonymousView;

class UserUuidController extends AbstractController
{


	/**
	 * @Route("/api/user/me", name="api_user_me", methods={"GET"})
	 * @OA\Get(
	 *     path="/api/user/me",
	 *     summary="Gives informations about me",
	 *     @OA\Response(response="200", description="Successful")
	 * )
	 */
	public function me()
	{
		$grantedRoles = array();
		if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
			array_push($grantedRoles, 'ROLE_SUPER_ADMIN');
		}
		if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
			array_push($grantedRoles, 'ROLE_ADMIN');
		}
		if($this->get('security.authorization_checker')->isGranted('ROLE_CLUB_MANAGER')) {
			array_push($grantedRoles, 'ROLE_CLUB_MANAGER');
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
			$me = new UserMeView($account->getUser(), $grantedRoles);
		} else {
			$me = new MeAnonymousView($grantedRoles);
		}

		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($me, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}


	/**
	 * @Route("/api/user/{uuid}", name="api_user_one", methods={"GET"}, requirements={"uuid"="[a-zA-Z0-9]{16}"})
	 * @IsGranted("ROLE_TEACHER")
	 * @OA\Get(
	 *     path="/api/user/{uuid}",
	 *     summary="Give an user",
	 *     @OA\Parameter(
     *         description="UUID of user",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(
     *             format="string",
     *             type="string",
     *             pattern="[a-zA-Z0-9]{16}"
     *         )
     *     ),
	 *     @OA\Response(
	 *         response="200",
	 *         description="Successful",
	 *         @OA\JsonContent(type="object", @OA\Items(ref="#/components/schemas/UserView"))
	 *     )
	 * )
	 */
	public function one($uuid)
	{
		if('me' === $uuid) {
			return $this->me();
		}
		$account = $this->getUser();
		$data = [];
		$userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);

		if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
			$data = $userRepository->findInAll($uuid);
		} elseif($this->get('security.authorization_checker')->isGranted("ROLE_CLUB_MANAGER")) {
			$data = $userRepository->findInMyClubs($account->getId(), $uuid);
// 		} elseif($this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
// 			$data = array($account->getUser());
		}
		$output = [];
		if(count($data) > 0) {
			$output = array('user' => new UserView($data[0]));
		}

		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($output, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}


	/**
	 * @Route("/api/user/{uuid}", name="api_user_update-one", methods={"PUT"}, requirements={"uuid"="[a-zA-Z0-9]{16}"})
	 * @IsGranted("ROLE_CLUB_MANAGER")
	 * @OA\Put(
	 *     path="/api/user/{uuid}",
	 *     summary="Update an user",
	 *     @OA\Parameter(
     *         description="UUID of user",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(
     *             format="string",
     *             type="string",
     *             pattern="[a-zA-Z0-9]{16}"
     *         )
     *     ),
	 *     @OA\RequestBody(
	 *         description="User object that needs to be added",
	 *         required=true,
	 *         @OA\JsonContent(ref="#/components/schemas/UserUpdate"),
	 *     ),
	 *     @OA\Response(response="200", description="Successful")
	 * )
	 */
	public function updateOne($uuid, Request $request, SerializerInterface $serializer, TranslatorInterface $translator, LoggerInterface $logger)
	{
		if(! self::one($uuid)) {
			$this->denyAccessUnlessGranted('ROLE_USER', 'User not found or access denied', 'User not found or access denied');
		}
		$requestUtil = new RequestUtil($serializer, $translator);

		try {
			$userUpdate = $requestUtil->validate($request, UserUpdate::class);
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
			$user = $service->update($this->getUser(), $uuid, $userUpdate, $logger);
		} catch (\Exception $e) {
			return ShortResponse::exception('Query failed, please try again shortly ('.$e->getMessage().')');
		}

		$output = array('user' => new UserView($user));
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($output, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}

	/**
	 * @Route("/api/user/{uuid}", name="api_user_delete-one", methods={"DELETE"}, requirements={"uuid"="[a-zA-Z0-9]{16}"})
	 * @IsGranted("ROLE_CLUB_MANAGER")
	 * @OA\Delete(
	 *     path="/api/user/{uuid}",
	 *     summary="Delete an user",
	 *     @OA\Parameter(
	 *         description="UUID of user",
	 *         in="path",
	 *         name="uuid",
	 *         required=true,
	 *         @OA\Schema(
	 *             format="string",
	 *             type="string",
	 *             pattern="[a-zA-Z0-9]{16}"
	 *         )
	 *     ),
	 *     @OA\RequestBody(
	 *         description="User object that needs to be added",
	 *         required=true,
	 *         @OA\JsonContent(ref="#/components/schemas/UserUpdate"),
	 *     ),
	 *     @OA\Response(response="200", description="Successful")
	 * )
	 */
	public function deleteOne($uuid, Request $request, SerializerInterface $serializer, TranslatorInterface $translator)
	{
		if(! self::one($uuid)) {
			$this->denyAccessUnlessGranted('ROLE_USER', 'User not found or access denied', 'User not found or access denied');
		}

		// TODO


// 		try {
// 			$service = new UserService($this->getDoctrine()->getManager(), $request);
// 		} catch (\Exception $e) {
// 			return ShortResponse::exception('Initialization failed, '.$e->getMessage());
// 		}

// 		try {
// 			$user = $service->update($this->getUser(), $uuid, $userUpdate);
// 		} catch (\Exception $e) {
// 			return ShortResponse::exception('Query failed, please try again shortly ('.$e->getMessage().')');
// 		}

// 		$output = array('user' => new UserView($user));
// 		$hateoas = HateoasBuilder::create()->build();
// 		$json = json_decode($hateoas->serialize($output, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}
}
