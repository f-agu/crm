<?php

namespace App\Controller\Api;

use primus852\ShortResponse\ShortResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Dao\SearchDao;

class SearchController extends AbstractController
{
	
	/**
 	 * @Route("/api/search", name="api_search", methods={"GET"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function search(Request $request)
	{
	    $page = intval($request->query->get('page', 0));
	    $elementbypage = intval($request->query->get('elementbypage', 20));
	    $offset = $page * $elementbypage;
	    
	    $query = $request->query->get('q', '');
	    
	    $data = array();
	    if($page >= 0 && $elementbypage >= 0 && strlen($query) >= 2) {
	        $search = new SearchDao($this->getDoctrine()->getManager(), $this->get('security.authorization_checker'));
	        $data = $search->search($query, $this->getUser(), $offset, $elementbypage);
	    }
	    
		return ShortResponse::success('searched', $data);
	}	
}
