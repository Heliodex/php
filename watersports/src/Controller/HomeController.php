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
		$user = $this->user($request);
		if (!$user)
			return $this->redirectToRoute("login");

		$number = Database::getRandomNumber();
		$products = Database::getProducts();

		return $this->finish($request, "home.html.twig", [
			"number" => $number,
			"products"=> $products,
		]);
	}
}
