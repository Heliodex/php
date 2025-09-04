<?php
require "config.php";

$db = mysqli_connect(
	$CONFIG["hostname"],
	$CONFIG["username"],
	$CONFIG["password"],
	$CONFIG["database"],
);

if ($db->connect_error)
	die("connection failed " . $db->connect_error);
