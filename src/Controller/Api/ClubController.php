<?php

namespace App\Controller\Api;

use App\Entity\Club;
use Hateoas\HateoasBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ClubView;
use App\Entity\ClubLocation;
use OpenApi\Annotations as OA;


class ClubController extends AbstractController
{

	/**
	 * @Route("/api/club", name="api_club_list-active", methods={"GET"})
	 * @OA\Get(
	 *     path="/api/club",
	 *     summary="List all active clubs",
	 *     @OA\Response(response="200", description="Successful")
	 * )
	 */
	public function listActive()
	{
		$clubs = $this->getDoctrine()->getManager()
			->getRepository(Club::class)
			->findAllActiveWithLocations();

		$output = array('clubs' => $clubs);
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($output, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}

	/**
	 * @Route("/api/club/{uuid}", name="api_club_one", methods={"GET"})
	 * @OA\Get(
	 *     path="/api/club/{uuid}",
	 *     summary="Give a club",
	 *     @OA\Parameter(
     *         description="UUID of club",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(
     *             format="string",
     *             type="string"
     *         )
     *     ),
	 *     @OA\Response(response="200", description="Successful")
	 * )
	 */
	public function one($uuid)
	{
		$clubs = $this->getDoctrine()->getManager()
			->getRepository(Club::class)
			->findBy(['uuid' => $uuid]);
		$output = [];
		if(count($clubs) > 0) {
			$clubloc = $this->getDoctrine()->getManager()
				->getRepository(ClubLocation::class)
				->findByClubs([$clubs[0]]);
			$output = array('club' => $clubloc[0]);
		}

		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($output, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}

}
