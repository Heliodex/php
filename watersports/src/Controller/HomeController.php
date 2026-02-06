<?php

namespace App\Controller;

use App\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends Base
{
	#[Route("/home")]
	#[IsGranted("ROLE_USER")]
	final public function index(): Response
	{
		$number = Database::getRandomNumber();

		return $this->render("home.html.twig", [
			"number" => $number,
		]);
	}
}
