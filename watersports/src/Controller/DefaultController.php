<?php

namespace App\Controller;

use App\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends Base
{
	#[Route("/")]
	final public function main(): Response
	{
		$number = Database::getRandomNumber();

		return $this->render("index.html.twig", [
			"number" => $number,
		]);
	}
}
