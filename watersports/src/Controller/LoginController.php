<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends Base
{
	#[Route("/login", methods: ["GET", "POST"], name: "login")]
	public function login(#[CurrentUser] ?User $user, AuthenticationUtils $authenticationUtils): Response
	{
		if ($user)
			return $this->redirectToRoute("home");

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
