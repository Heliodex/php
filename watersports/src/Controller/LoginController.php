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
		if ($request->getSession()->get("user"))
			return $this->redirectToRoute("home");

		$login = new Login();
		$form = $this->createForm(LoginType::class, $login);

		$finish = fn() => $this->finish($request, "login.html.twig", [
			"form" => $form,
		]);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			$username = $data->username;
			$password = $data->password;

			$user = Database::checkUser($username, $password);
			if (!$user) {
				// add error message
				$form->addError(new FormError("Incorrect username or password"));
				return $finish();
			}

			// set session and return to logged-in page
			$session = $request->getSession();
			$session->set("user", $user);

			return $this->redirectToRoute("home");
		}

		return $finish();
	}
}
