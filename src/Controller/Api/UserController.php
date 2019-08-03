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

class UserController extends AbstractController
{

	/**
	 * @Route("/api/user", name="api_user_list-all", methods={"GET"})
	 * @IsGranted("ROLE_TEACHER")
	 * @OA\Get(
	 *     path="/api/user",
	 *     summary="List of users",
	 *     @OA\Parameter(
     *         description="page number",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(
     *             format="string",
     *             type="string"
     *         )
     *     ),
	 *     @OA\Parameter(
     *         description="max number of result in a page",
     *         in="query",
     *         name="n",
     *         required=false,
     *         @OA\Schema(
     *             format="string",
     *             type="string"
     *         )
     *     ),
	 *     @OA\Response(
	 *         response="200",
	 *         description="Successful",
	 *         @OA\JsonContent(type="object", items = @OA\Items(ref="#/components/schemas/UsersView"))
	 *     )
	 * )
	 */
	public function listAll(Request $request, LoggerInterface $logger)
	{
		$pager = new Pager($request);

		$account = $this->getUser();
		$data = array();
		if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
			$data = $this->getDoctrine()->getManager()
				->getRepository(User::class)
				->findBy([], [
					'lastname' => 'ASC',
					'firstname' => 'ASC'
				], $pager->getElementByPage() + 1, $pager->getOffset());
		} elseif($this->get('security.authorization_checker')->isGranted("ROLE_TEACHER")) {
			$data = $this->getDoctrine()->getManager()
				->getRepository(User::class)
				->findInMyClubs($account->getId(), null, $pager->getOffset(), $pager->getElementByPage() + 1);
		} elseif($this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
			$data = array($account->getUser());
		}

		$datasliced = array_slice($data, 0, $pager->getElementByPage());

		$userviews = array();
		foreach ($datasliced as &$u) {
			array_push($userviews, new UserView($u));
		}
		$pagination = new Pagination($pager->getPage(), $pager->getElementByPage(), count($data) > $pager->getElementByPage());
		$output = new UsersView($pagination, $userviews);
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($output, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}

	/**
	 * @Route("/api/user/{uuid}", name="api_user_one", methods={"GET"})
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
     *             type="string"
     *         )
     *     ),
	 *     @OA\Response(
	 *         response="200",
	 *         description="Successful",
	 *         @OA\JsonContent(type="object", @OA\Items(ref="#/components/schemas/UserView"))
	 *     )
	 * )
	 */
	public function one($uuid, LoggerInterface $logger)
	{
		$account = $this->getUser();
		$data = array();
		if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
			$data = $this->getDoctrine()->getManager()
				->getRepository(User::class)
				->findBy(['uuid' => $uuid]);
		} elseif($this->get('security.authorization_checker')->isGranted("ROLE_TEACHER")) {
			$data = $this->getDoctrine()->getManager()
				->getRepository(User::class)
				->findInMyClubs($account->getId(), $uuid);
		} elseif($this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
			$data = array($account->getUser());
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
 	 * @Route("/api/user", name="api_user_create-one", methods={"POST"})
	 * @IsGranted("ROLE_TEACHER")
	 * @OA\Post(
	 *     path="/api/user",
	 *     summary="Create an user",
     *     @OA\RequestBody(
     *         description="User object that needs to be added",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreate"),
     *     ),
	 *     @OA\Response(response="200", description="Successful")
	 * )
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
			$user = $service->create($this->getUser(), $userCreate);
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

}
