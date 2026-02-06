<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LogoutController extends Base
{
	#[Route("/logout", methods: ["POST"])]
	#[IsGranted("ROLE_USER")]
	final public function index(Security $security): Response
	{
		return $security->logout();
	}
}
