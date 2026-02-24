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

		if ($request->isMethod("POST")) {
			$productId = $request->request->get("productId");
			Database::changeCart($user->id, $productId, false);
		}

		$cart = Database::getCart($user->id);

		return $this->finish($request, "cart.html.twig", [
			"cart" => $cart,
		]);
	}
}
