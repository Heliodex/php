<?php

namespace App\Controller;

use App\Entity\Register;
use App\Form\Type\RegisterType;
use App\{Database, Log, function requireLogout};
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends Base
{
	#[Route("/register")]
	final public function index(Request $request): Response
	{
		$redir = requireLogout($request, fn(string $r) => $this->redirectToRoute($r));
		if ($redir)
			return $redir;

		$register = new Register();
		$form = $this->createForm(RegisterType::class, $register);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			Log::info("Registration attempt with username {$data->username}");

			$username = $data->username;
			$email = $data->email;
			$password = $data->password;
			$confirmPassword = $data->confirmPassword;

			if ($password !== $confirmPassword) {
				$form->addError(new FormError("Passwords do not match"));
				return $this->render("register.html.twig", [
					"form" => $form,
				]);
			}

			if (!Database::registerUser($username, $email, $password)) {
				$form->addError(new FormError("Registration failed. Username or email may already be taken."));
				return $this->render("register.html.twig", [
					"form" => $form,
				]);
			}

			Log::info("Registration successful for username {$username}");
			// Redirect to login page after successful registration
			return $this->redirectToRoute("login");
		}

		return $this->render("register.html.twig", [
			"form" => $form,
		]);
	}
}
