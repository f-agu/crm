<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Util\TreeWalker;
use App\Util\DiffTool;

class SandBoxController extends AbstractController
{
	/**
	 * @Route("/sb", name="web_test", methods={"GET"})
	 */
	public function test(Request $request)
	{
		$dataprevious = $this->getDoctrine()->getManager()
			->getRepository(User::class)
			->findBy(['uuid' => 'nISaDE5iWrkZL62b'])[0];
		
		$datanew = $this->getDoctrine()->getManager()
			->getRepository(User::class)
			->findBy(['uuid' => 'nISaDE5iWrkZL622'])[0];
		
		$arrayprev = DiffTool::toArray($dataprevious);
		$arraynew = DiffTool::toArray($datanew);

		$treewalker = new TreeWalker([]);
		$result_array = $treewalker->getdiff($arrayprev, $arraynew);
		//echo $result_array;
		
		return new Response($result_array, 200, array(
			'Content-Type' => 'text/html'
		));
	}

}
