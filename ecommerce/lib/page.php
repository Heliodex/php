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

		$links = [];
		if ($session->isLoggedIn()) {
			$links[] = "<a href=\"/home.php\">Home</a>";
			$links[] = "<a href=\"/users.php\">Users</a>";
			$links[] = "<a href=\"/tracks.php\">Tracks</a>";
			$links[] = "<a href=\"/addcd.php\">Add CD</a>";
			$links[] = "<a href=\"/profile.php\">Profile</a>";
			$links[] = "<form method=\"post\" action=\"/logout.php\" class=\"inline-form\"><button>Log out</button></form>";
		} else {
			$links[] = "<a href=\"/login.php\">Log in</a>";
			$links[] = "<a href=\"/register.php\">Register</a>";
		}

		$strlinks = implode("", array_map(fn($link) => "<li>$link</li>", $links)); 

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
		<nav><ul>{$strlinks}</ul></nav>
	</header>
	<main>
HTML;
	}

	function __destruct()
	{
		// get page generation time
		global $_PAGE_START_TIME;
		$time = round((microtime(true) - $_PAGE_START_TIME) * 1000, 2);

		echo <<<HTML
	</main>
	<footer>
		<p>🅮 2025 My E-commerce Site</p>
		<p>Page generated in {$time}ms</p>
	</footer>
</body>

</html>
HTML;
	}
}
