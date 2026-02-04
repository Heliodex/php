<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
	#[Route("/")]
	public function index(): Response
	{
		$DB = new \App\Database();
		
		$number = $DB->getRandomNumber();

		return $this->render("index.html.twig", [
			"number" => $number,
		]);
	}
}
