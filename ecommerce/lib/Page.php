<?php

declare(strict_types=1);

require_once "lib/site.php";

$site = new Site("My E-commerce Site", "http://localhost:8000");

class Page
{
	private string $title;

	public function __construct(string $title)
	{
		$this->title = $title;
		echo <<<HTML
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/static/main.css">
	<title>{$this->title}</title>
</head>

<body>
	<header>
		<nav>
			<ul>
				<li><a href="/home.php">Home</a></li>
				<li><a href="/login.php">Log in</a></li>
			</ul>
		</nav>
	</header>
	<main>
HTML;
	}

	public function __destruct()
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
