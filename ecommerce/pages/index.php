<?php

require_once "lib/database.php";

$query = $DB->query("SELECT * FROM UsersAuto");

$rows = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
	echo "<ul>";
	foreach ($row as $key => $value)
		echo "<li>$key: $value</li>";
	echo "</ul><hr>";
}
