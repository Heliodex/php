<?php

declare(strict_types=1);

class Session
{
	function start(): void
	{
		if (session_status() === PHP_SESSION_NONE)
			session_start();
	}

	function isLoggedIn(): bool
	{
		self::start();
		return isset($_SESSION["user"]);
	}

	function requireLogin(): void
	{
		if (self::isLoggedIn()) return;

		header("Location: login.php");
		die;
	}

	function requireLogout(): void
	{
		if (!self::isLoggedIn()) return;

		header("Location: home.php");
		die;
	}

	function __construct(bool $loginRequired)
	{
		if ($loginRequired)
			$this->requireLogin();
		else
			$this->requireLogout();
	}
}
