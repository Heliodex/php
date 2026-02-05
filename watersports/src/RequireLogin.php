<?php

namespace App;

use Symfony\Component\HttpFoundation\{Request, RedirectResponse};

function requireLogin(Request $request, callable $redirect): ?RedirectResponse
{
	if (!$request->getSession()->has("id"))
		return $redirect("index");

	return null;

}

function requireLogout(Request $request, callable $redirect): ?RedirectResponse
{
	if ($request->getSession()->has("id"))
		return $redirect("home");

	return null;
}
