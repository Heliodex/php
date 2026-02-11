<?php

namespace App\Controller;

use App\Database;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

class Base extends AbstractController
{
	final protected function user(Request $request): ?User
	{
		$session = $request->getSession();
		$sess = $session->get("id");
		if (!$sess)
			return null;

		return Database::getUserBySessionId($sess);
	}

	final protected function finish(Request $request, string $view, array $parameters = []): Response
	{
		$user = $this->user($request);

		$newParams = [
			"user" => $user,
		];

		return parent::render(
			$view,
			[...$parameters, ...$newParams],
		);
	}
}
