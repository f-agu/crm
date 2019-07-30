<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
	/**
 	 * @Route("/search", name="web_search", methods={"GET"})
	 */
	public function search(Request $request)
	{
	    $user = $this->getUser();
	    $response = $this->forward('App\Controller\Api\SearchController::search', ['request' => $request]);
	    $json = json_decode($response->getContent());
	    return $this->render('search.html.twig', [
	        'connectedUser' => $user,
	        'searchResult' => $json
	    ]);
	}

}
