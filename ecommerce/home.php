<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Home");

$query = $DB->query("SELECT * FROM cd");
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Home</h1>

<h2>CDs in Database</h2>

<?php
foreach ($rows as $row) {
	$id = htmlspecialchars($row["id"]);
?>
	<a href="cd.php?id=<?= urlencode($id) ?>">View CD <?= $id ?></a>
	<ul>
		<?php foreach ($row as $key => $value) echo "<li>$key: $value</li>"; ?>
	</ul>
	<hr>
<?php
}

if (count($rows) === 0)
	echo "<p>No CDs found in the database.</p>";
