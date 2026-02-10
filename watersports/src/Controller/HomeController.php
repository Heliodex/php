<?php

namespace App\Controller;

use App\Database;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends Base
{
	#[Route("/home", name: "home")]
	final public function home(Request $request): Response
	{
		if (!$request->getSession()->get("user"))
			return $this->redirectToRoute("login");

		$number = Database::getRandomNumber();

		return $this->render("home.html.twig", [
			"number" => $number,
		]);
	}
}
