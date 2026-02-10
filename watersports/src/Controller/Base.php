<?php

namespace App\Controller;

use App\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

class Base extends AbstractController
{
	final protected function finish(Request $request, string $view, array $parameters = []): Response
	{
		$session = $request->getSession();
		$user = $session->get("user");

		$newParams = [
			"user" => $user,
		];

		return parent::render(
			$view,
			[...$parameters, ...$newParams],
		);
	}
}
