<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

$id = $_GET["id"] ?? null;
if ($id === null)
	require_once "lib/404.php";

// load and output the cover image file
$coverPath = __DIR__ . "/covers/$id";

if (!is_file($coverPath)) {
	http_response_code(404);
	die;
}

$coverData = file_get_contents($coverPath);
if ($coverData === false) {
	http_response_code(500);
	die;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mediaType = finfo_buffer($finfo, $coverData);

header("Content-Type: $mediaType");
echo $coverData;
