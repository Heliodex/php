<?php

require_once "lib/site.php";
require_once "lib/page.php";

$site = new Site("My E-commerce Site", "http://localhost:8000");
$pageBuilder = new Page($site->name);

// get current url path
$currentPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// get page from the pages directory
$pageFile = __DIR__ . "/pages/$currentPath.php";
if ($currentPath === "/")
	$pageFile = __DIR__ . "/pages/index.php";

if (!file_exists($pageFile)) {
	$pageBuilder->err404();
	$pageBuilder->render();
	die(404);
}

ob_start();
require_once $pageFile;
$pageContent = ob_get_clean();

$pageBuilder->addContent($pageContent);
$pageBuilder->render();
