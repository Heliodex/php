<?php

declare(strict_types=1);

require_once "config.php";

$DB = new PDO("mysql:host={$CONFIG['DB_HOST']};dbname={$CONFIG['DB_NAME']}", $CONFIG['DB_USER'], $CONFIG['DB_PASSWORD']);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$DB->exec(
	"CREATE TABLE IF NOT EXISTS user (
		id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
		username VARCHAR(21) UNIQUE NOT NULL,
		email VARCHAR(255) UNIQUE NOT NULL,
		password VARCHAR(255) NOT NULL
	);"
);
