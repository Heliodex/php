<?php

namespace App\Controller;

use App\Database;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends Base
{
	#[Route("/", name: "index")]
	final public function index(Request $request): Response
	{
		$user = $this->user($request);
		if ($user)
			return $this->redirectToRoute("home");

		$number = Database::getRandomNumber();

		return $this->finish($request, "index.html.twig", [
			"number" => $number,
		]);
	}
}
