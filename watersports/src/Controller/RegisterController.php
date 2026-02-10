<?php

namespace App\Controller;

use App\Entity\Register;
use App\Form\Type\RegisterType;
use App\Database;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends Base
{
	#[Route("/register", name: "register")]
	final public function register(Request $request): Response
	{
		if ($request->getSession()->get("user"))
			return $this->redirectToRoute("home");


		$register = new Register();
		$form = $this->createForm(RegisterType::class, $register);

		$finish = fn() => $this->finish($request, "register.html.twig", [
			"form" => $form,
		]);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			$email = $data->email;
			$password = $data->password;
			$confirmPassword = $data->confirmPassword;

			if ($password !== $confirmPassword) {
				$form->addError(new FormError("Passwords do not match"));
				return $finish();
			}

			$newUser = Database::registerUser($email, $password);
			if (!$newUser) {
				$form->addError(new FormError("Registration failed. An account may already be registered with this email address."));
				return $finish();
			}

			$session = $request->getSession();
			$session->set("user", $newUser);

			// Redirect to home page after successful registration
			return $this->redirectToRoute("home");
		}

		return $finish();
	}
}
