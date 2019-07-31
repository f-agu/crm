<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Dao\SearchDao;
use Symfony\Component\HttpFoundation\Response;
use Hateoas\HateoasBuilder;
use Psr\Log\LoggerInterface;
use App\Util\Pager;

class SearchController extends AbstractController
{

	/**
	 * @Route("/api/search", name="api_search", methods={"GET"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function search(Request $request, LoggerInterface $logger)
	{
		$pager = new Pager($request);
		$query = trim($request->query->get('q', ''));
		$logger->debug('query: ['.$query.']');
		$searched = array();
		if($pager->isValid() && strlen($query) >= 2) {
			$search = new SearchDao($this->getDoctrine()->getManager(), $this->get('security.authorization_checker'));
			$searched = $search->search($query, $this->getUser(), $pager->getOffset(), $pager->getElementByPage());
		}

		$result = [
			'q' => $query,
			'page' => $pager->getPage(),
			'n' => $pager->getElementByPage(),
			'results' => $searched['results'],
			'hasmore' => $searched['hasmore']
		];
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($result, 'json'));

		return new Response(json_encode($json), 200, array(
			'Content-Type' => 'application/hal+json'
		));
	}
}
