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
<title>{$this->title}</title>
</head>

<body>
{$this->content}
</body>

</html>
HTML;
	}
}
