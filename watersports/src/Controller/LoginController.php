<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends Base
{
	#[Route("/login")]
	final public function index(): Response
	{
		$DB = new \App\Database();

		$number = $DB->getRandomNumber();

		$form = $this->createFormBuilder($data, $options);

		return $this->render("login.html.twig", [
			"number" => $number,
		]);
	}
}
