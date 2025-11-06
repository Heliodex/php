<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Users");

$query = $DB->query("SELECT id, username, email FROM user;");
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Users</h1>

<?php
foreach ($rows as $row) {
	echo "<ul>";
	foreach ($row as $key => $value)
		echo "<li>$key: $value</li>";
	echo "</ul><hr>";
}
