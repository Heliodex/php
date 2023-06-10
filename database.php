<?php

require("config.php");

$db = new mysqli(
	$CONFIG["hostname"],
	$CONFIG["username"],
	$CONFIG["password"],
	$CONFIG["database"],
);

if ($db->connect_error) {
	die("connection failed " . $db->connect_error);
}

echo $db->host_info;