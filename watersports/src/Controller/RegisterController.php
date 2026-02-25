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
	#[Route("/register", name: "register", options: ["sitemap" => true])]
	final public function register(Request $request): Response
	{
		$user = $this->user($request);
		if ($user)
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

			$sess = Database::registerUser($email, $password);
			if (!$sess) {
				$form->addError(new FormError("Registration failed. An account may already be registered with this email address."));
				return $finish();
			}

			$session = $request->getSession();
			$session->set("id", $sess);

			// Redirect to home page after successful registration
			return $this->redirectToRoute("home");
		}

		return $finish();
	}
}
