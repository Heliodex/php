<?php

namespace App\Controller;

use App\Entity\Login;
use App\Form\Type\LoginType;
use App\{Database, Log};
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Base
{
	#[Route("/login")]
	final public function index(Request $request): Response
	{
		$login = new Login();
		$form = $this->createForm(LoginType::class, $login);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			Log::info("Login attempt with username {$data->username}");

			$username = $data->username;
			$password = $data->password;

			if (Database::checkUser($username, $password)) {
				Log::info("Login successful for username {$username}");
				// Redirect to a secure page or dashboard
				return $this->redirectToRoute("dashboard");
			}

			// add error message
			$form->addError(new FormError("Invalid username or password"));
		}

		return $this->render("login.html.twig", [
			"form" => $form,
		]);
	}
}
