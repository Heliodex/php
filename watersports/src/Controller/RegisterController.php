<?php

namespace App\Controller;

use App\Entity\{Register, User};
use App\Form\Type\RegisterType;
use App\{Database, Log};
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class RegisterController extends Base
{
	#[Route("/register", name: "register")]
	final public function register(#[CurrentUser] ?User $user, Request $request): Response
	{
		// Redirect authenticated users to /home
		if ($user)
			return $this->redirectToRoute("home");


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
			return $this->redirectToRoute("home");
		}

		return $this->render("register.html.twig", [
			"form" => $form,
		]);
	}
}
