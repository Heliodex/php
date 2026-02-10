<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class LogoutController extends Base
{
	#[Route("/logout", methods: ["POST"], name: "logout")]
	#[IsGranted("IS_AUTHENTICATED")]
	final public function logout(Request $request): Response
	{
		$session = $request->getSession();
		$session->remove("user");

		return $this->redirectToRoute("login");
	}
}
