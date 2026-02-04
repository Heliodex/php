<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Base extends AbstractController
{
	final protected function render(string $view, array $parameters = [], ?Response $response = null): Response
	{
		// add nav user parameter if not already set
		$newParams = [
			"user" => "Guest",
		];

		return parent::render(
			$view,
			[...$parameters, ...$newParams],
			$response
		);
	}
}
