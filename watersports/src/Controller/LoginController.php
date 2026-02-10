<?php

namespace App\Controller;

use App\{Database, Log};
use App\Entity\Login;
use App\Form\Type\LoginType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends Base
{
	#[Route("/login", methods: ["GET", "POST"], name: "login")]
	final public function login(Request $request): Response
	{
		$login = new Login();
		$form = $this->createForm(LoginType::class, $login);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			Log::info("Login attempt with username {$data->username}");

			$username = $data->username;
			$password = $data->password;

			$user = Database::checkUser($username, $password);
			if ($user) {
				Log::info("Login successful for username {$username}");

				// set session and return to logged-in page
				$session = $request->getSession();
				$session->set("user", $user);

				return $this->redirectToRoute("home");
			}

			// add error message
			$form->addError(new FormError("Incorrect username or password"));
		}

		return $this->render("login.html.twig", [
			"form" => $form,
		]);
	}
}
