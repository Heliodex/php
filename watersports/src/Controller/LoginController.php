<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Base
{
	#[Route("/login", methods: ["GET", "POST"], name: "login")]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render("login.html.twig", [
			"last_username" => $lastUsername,
			"error" => $error,
		]);
	}
}
