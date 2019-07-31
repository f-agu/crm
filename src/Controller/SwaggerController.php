<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SwaggerController extends AbstractController
{
	/**
 	 * @Route("/swagger-config.json", name="web_swagger-config")
	 */
	public function index()
	{
		$openapi = \OpenApi\scan('../src');
		return new Response($openapi->toJson(), 200, array(
			'Content-Type: application/x-yaml'
		));
	}


}
