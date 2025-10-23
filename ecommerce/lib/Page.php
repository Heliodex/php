<?php

class Page
{
	private string $title;
	private string $content = "";

	public function __construct(string $title)
	{
		$this->title = $title;
	}

	public function addContent(string $html): void
	{
		$this->content .= $html;
	}

	public function err404(): void
	{
		http_response_code(404);
		$this->addContent(
			<<<HTML
<h1>404 Not Found</h1>
<p>The page you requested could not be found.</p>
HTML
		);
	}

	public function err500(string $message = ""): void
	{
		http_response_code(500);
		$this->addContent(
			<<<HTML
<h1>500 Internal Server Error</h1>
<p>There was an internal server error.</p>
HTML
		);
		if ($message !== "")
			$this->addContent("<p>$message</p>");
	}

	public function render(): void
	{
		echo <<<HTML
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css">
	<link rel="stylesheet" href="/main.css">
	<title>{$this->title}</title>
</head>

<body>
	<header>
		<nav>
			<ul>
				<li><a href="/">Home</a></li>
				<li><a href="/login">Log in</a></li>
			</ul>
		</nav>
	</header>
	<main>
		{$this->content}
	</main>
</body>

</html>
HTML;
	}
}
