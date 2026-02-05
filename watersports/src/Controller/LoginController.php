<?php

namespace App\Controller;

use App\Entity\Login;
use App\Form\Type\LoginType;
use App\{Database, Log, function requireLogout};
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends Base
{
	#[Route("/login")]
	final public function index(Request $request): Response
	{
		$redir = requireLogout($request, fn(string $r) => $this->redirectToRoute($r));
		if ($redir)
			return $redir;

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

				// set session
				$session = $request->getSession();
				$session->set("id", $user->id);

				// Redirect to a secure page or dashboard
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
