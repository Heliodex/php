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

		if ($request->isMethod("POST")) {
			// get param
			$productId = $request->request->get("productId");
			$addOrRemove = $request->request->get("action");
			Database::changeCart($user->id, $productId, $addOrRemove === "add");
		}

		$products = Database::getProducts($user->id);

		return $this->finish($request, "home.html.twig", [
			"products" => $products,
		]);
	}
}
