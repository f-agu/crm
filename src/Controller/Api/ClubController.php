<?php

namespace App\Controller\Api;

use App\Entity\Club;
use Hateoas\HateoasBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ClubView;
use App\Entity\ClubLocation;


class ClubController extends AbstractController
{

    /**
     * @Route("/api/club", name="api_club_list-active", methods={"GET"})
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
