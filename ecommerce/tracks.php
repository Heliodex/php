<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Tracks");

$query = $DB->query("SELECT id, name, trackNumber, duration, cdId FROM track");
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Tracks</h1>

<?php if (count($rows) !== 0) { ?>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Track number</th>
				<th>Duration</th>
				<th>CD ID</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) { ?>
				<tr>
					<?php
					foreach ($row as $key => $value)
						if ($key === "id") {
							$shortId = substr(htmlspecialchars($value), 0, 6);
							echo "<td>$shortId</td>";
						} elseif ($key === "cdId") {
							$shortId = substr(htmlspecialchars($value), 0, 6);
							echo "<td><a href=\"cd.php?id=" . urlencode(htmlspecialchars($value)) . "\">$shortId</a></td>";
						} else
							echo "<td>$value</td>"; ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else
	echo "<p>No tracks found in the database</p>";
