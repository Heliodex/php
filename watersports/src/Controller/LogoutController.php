<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class LogoutController extends Base
{
	#[Route("/logout", methods: ["POST"], name: "logout")]
	#[IsGranted("IS_AUTHENTICATED")]
	final public function logout(Security $security): Response
	{
		return $security->logout();
	}
}
