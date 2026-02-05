<?php

namespace App\Controller;

use App\{Database, function requireLogin};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends Base
{
	#[Route("/home")]
	final public function main(Request $request): Response
	{
		$redir = requireLogin($request, fn(string $r) => $this->redirectToRoute($r));
		if ($redir)
			return $redir;

		$number = Database::getRandomNumber();

		return $this->render("home.html.twig", [
			"number" => $number,
		]);
	}
}
