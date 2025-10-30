<?php

declare(strict_types=1);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Home");

$query = $DB->query("SELECT * FROM UsersAuto");

$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Home</h1>

<?php
foreach ($rows as $row) {
	echo "<ul>";
	foreach ($row as $key => $value)
		echo "<li>$key: $value</li>";
	echo "</ul><hr>";
}
