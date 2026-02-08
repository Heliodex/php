<?php

namespace App\Controller;

use App\Database;
use App\Entity\User;
use App\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
// use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HomeController extends Base
{
	#[Route("/home", name: "home")]
	final public function home(#[CurrentUser] ?User $user): Response
	{
		if (!$user) {
			Log::info("Unauthorized access attempt to /home!");
			return $this->redirectToRoute("login");
		}

		$number = Database::getRandomNumber();

		return $this->render("home.html.twig", [
			"number" => $number,
		]);
	}
}
