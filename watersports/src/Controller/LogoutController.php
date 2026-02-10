<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends Base
{
	#[Route("/logout", methods: ["POST"], name: "logout")]
	final public function logout(Request $request): Response
	{
		$session = $request->getSession();
		$session->remove("user");

		return $this->redirectToRoute("login");
	}
}
