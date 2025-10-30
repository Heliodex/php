<?php

declare(strict_types=1);

require_once "config.php";

$DB = new PDO("mysql:host={$CONFIG['DB_HOST']};dbname={$CONFIG['DB_NAME']}", $CONFIG['DB_USER'], $CONFIG['DB_PASSWORD']);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
