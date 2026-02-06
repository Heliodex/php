<?php

namespace App\Controller;

use App\{Database, function requireLogout};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends Base
{
	#[Route("/")]
	final public function main(Request $request): Response
	{
		$redir = requireLogout($request, $this->redirectToRoute(...));
		if ($redir)
			return $redir;

		$number = Database::getRandomNumber();

		return $this->render("index.html.twig", [
			"number" => $number,
		]);
	}
}
