<?php

require_once "lib/database.php";

$query = $DB->query("SELECT * FROM UsersAuto");

$rows = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
	foreach ($row as $key => $value) {
		echo "<p>$key: $value</p>";
	}
	echo "<hr />";
}
