<?php

class Site
{
	public string $name;
	public string $url;

	public function __construct(string $name, string $url)
	{
		$this->name = htmlspecialchars($name);
		$this->url = filter_var($url, FILTER_SANITIZE_URL);
	}
}
