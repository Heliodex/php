<?php

require_once "config.php";

$DB = new mysqli($CONFIG["DB_HOST"], $CONFIG["DB_USER"], $CONFIG["DB_PASSWORD"], $CONFIG["DB_NAME"]);
if ($DB->connect_errno)
	echo "Cannot connect to the database: $DB->connect_error";
