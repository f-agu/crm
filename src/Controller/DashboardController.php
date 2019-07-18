<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
	public function dashboard($blockName, $templateName): Response
	{
		//$this->denyAccessUnlessGranted('ROLE_USER');
		$user = $this->getUser();
		return $this->render('dashboard.html.twig',
			[	'user' => $user,
				'blockName' => $blockName,
				'templateName' => $templateName
			]);
	}
}
