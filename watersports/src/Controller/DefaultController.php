<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends Base
{
	#[Route("/")]
	final public function main(): Response
	{
		$DB = new \App\Database();

		$number = $DB->getRandomNumber();

		return $this->render("index.html.twig", [
			"number" => $number,
		]);
	}
}
