<?php

namespace App\Controller;

use App\Database;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class DefaultController extends Base
{
	#[Route("/", name: "index")]
	final public function index(#[CurrentUser] ?User $user): Response
	{
		// Redirect authenticated users to /home
		if ($user)
			return $this->redirectToRoute("home");

		$number = Database::getRandomNumber();

		return $this->render("index.html.twig", [
			"number" => $number,
		]);
	}
}
