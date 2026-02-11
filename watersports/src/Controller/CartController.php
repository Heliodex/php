<?php

namespace App\Controller;

use App\Database;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends Base
{
	#[Route("/cart", name: "cart")]
	final public function home(Request $request): Response
	{
		$user = $this->user($request);
		if (!$user)
			return $this->redirectToRoute("login");

		$number = Database::getRandomNumber();

		return $this->finish($request, "cart.html.twig", [
			"number" => $number,
		]);
	}
}
