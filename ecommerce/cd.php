<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

$title = $_GET["title"] ?? null;
if ($title === null)
	require_once "lib/404.php";

require_once "lib/database.php";
require_once "lib/page.php";

$query = $DB->prepare("SELECT * FROM cd WHERE title = ?");
$query->execute([$title]);
$cd = $query->fetch(PDO::FETCH_ASSOC);

if ($cd === false)
	require_once "lib/404.php";

$_ = new Page("CD Details");
?>

<h1>CD Details</h1>

<ul>
	<?php foreach ($cd as $key => $value) echo "<li>$key: $value</li>"; ?>
</ul>
<hr>