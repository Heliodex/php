<?php

declare(strict_types=1);

require_once "lib/session.php";
require_once "lib/site.php";

$site = new Site("My E-commerce Site", "http://localhost:8000");

class Page
{
	private string $title;

	function __construct(string $title)
	{
		$this->title = $title;

		$session = new Session();

		$links = "";
		if ($session->isLoggedIn()) {
			$links .= "<li><a href=\"/home.php\">Home</a></li>";
			$links .= "<li><a href=\"/users.php\">Users</a></li>";
			$links .= "<li><a href=\"/tracks.php\">Tracks</a></li>";
			$links .= "<li><a href=\"/addcd.php\">Add CD</a></li>";
			$links .= "<li><form method=\"post\" action=\"/logout.php\" class=\"inline-form\"><button type=\"submit\">Log out</button></form></li>";
		} else {
			$links .= "<li><a href=\"/login.php\">Log in</a></li>";
			$links .= "<li><a href=\"/register.php\">Register</a></li>";
		}

		echo <<<HTML
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/static/main.css">
	<title>{$this->title}</title>
	<script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.8/dist/htmx.min.js" integrity="sha384-/TgkGk7p307TH7EXJDuUlgG3Ce1UVolAOFopFekQkkXihi5u/6OCvVKyz1W+idaz" crossorigin="anonymous"></script>
</head>

<body hx-boost="true">
	<header>
		<nav><ul>{$links}</ul></nav>
	</header>
	<main>
HTML;
	}

	function __destruct()
	{
		echo <<<HTML
	</main>
	<footer>
		<p>🅮 2025 My E-commerce Site</p>
	</footer>
</body>

</html>
HTML;
	}
}
