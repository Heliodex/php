<?php

namespace App\Controller;

use App\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends Base
{
	#[Route("/home", name: "home")]
	#[IsGranted("IS_AUTHENTICATED")]
	final public function home(): Response
	{
		$number = Database::getRandomNumber();

		return $this->render("home.html.twig", [
			"number" => $number,
		]);
	}
}
