<?php

namespace App\Controller;

use App\Database;
use App\Entity\Login;
use App\Form\Type\LoginType;
use App\Log;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends Base
{
	#[Route("/login")]
	final public function main(Request $request): Response
	{
		$DB = new Database();

		$login = new Login();
		$form = $this->createForm(LoginType::class, $login);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data =  $form->getData();

			Log::info("Login attempt with username {$data->username}");

			$username = $data->username;
			$password = $data->password;

			if ($username === "admin" && $password === "admin")
				return new Response("Login successful!");

			// add error message
			$form->addError(new FormError("Invalid username or password"));
		}

		return $this->render("login.html.twig", [
			"form" => $form,
		]);
	}
}
