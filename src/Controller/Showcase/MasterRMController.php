<?php

namespace App\Controller\Showcase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MasterRMController extends AbstractController
{
	/**
	* @Route("/master-rm", name="master-rm")
	*/
	public function viewMasterRM(): Response
	{
		$user = $this->getUser();
		return $this->render('showcase/master-rm.html.twig', [
			'connectedUser' => $user
		]);
	}
}
