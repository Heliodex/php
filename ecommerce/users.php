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
?>
	<ul>
		<?php foreach ($row as $key => $value) echo "<li>$key: $value</li>"; ?>
	</ul>
	<hr>
<?php
}

if (count($rows) === 0)
	echo "<p>No users found in the database. (how are you here? this requires you to be logged in to see this page...)</p>";
