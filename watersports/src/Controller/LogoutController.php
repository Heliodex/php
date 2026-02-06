<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use function App\requireLogin;

class LogoutController extends Base
{
	#[Route("/logout", methods: ["POST"])]
	final public function index(Request $request, Security $security): Response
	{
		$redir = requireLogin($request, $this->redirectToRoute(...));
		if ($redir)
			return $redir;

		return $security->logout();
	}
}
