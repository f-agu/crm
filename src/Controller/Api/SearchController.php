<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Dao\SearchDao;
use Symfony\Component\HttpFoundation\Response;
use Hateoas\HateoasBuilder;
use Psr\Log\LoggerInterface;

class SearchController extends AbstractController
{

	/**
 	 * @Route("/api/search", name="api_search", methods={"GET"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function search(Request $request)
	{
	    $page = intval($request->query->get('page', 0));
	    $elementbypage = intval($request->query->get('n', 20));
	    $offset = $page * $elementbypage;
	    $query = trim($request->query->get('q', ''));

	    $data = array();
	    if($page >= 0 && $elementbypage >= 0 && strlen($query) >= 2) {
	        $search = new SearchDao($this->getDoctrine()->getManager(), $this->get('security.authorization_checker'));
	        $data = $search->search($query, $this->getUser(), $offset, $elementbypage);
	    }

		$result = [
			'q' => $query,
			'page' => $page,
			'n' => $elementbypage,
			'results' => $data
		];
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($result, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}
}
