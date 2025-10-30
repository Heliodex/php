<?php

declare(strict_types=1);

require_once "lib/site.php";
require_once "lib/page.php";

$site = new Site("My E-commerce Site", "http://localhost:8000");
$pageBuilder = new Page($site->name);

// get current url path
$currentPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($currentPath === "/")
	$currentPath = "/index";

// try to serve as a static file
$staticFile = __DIR__ . "/static/$currentPath";
if (file_exists($staticFile)) {
	// add correct content type header
	header("Content-Type: " . mime_content_type($staticFile));
	readfile($staticFile);
	die();
}

// get page from the pages directory
$pageFile = __DIR__ . "/pages$currentPath.php";

if (str_starts_with($currentPath, "/lib") || !file_exists($pageFile)) {
	$pageBuilder->err404();
	$pageBuilder->render();
	die(404);
}

ob_start();
require_once $pageFile;
$pageContent = ob_get_clean();

$pageBuilder->addContent($pageContent);
$pageBuilder->render();
