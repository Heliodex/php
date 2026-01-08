<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";

$searchQuery = $_GET["search"] ?? "";

$qtext = "SELECT id, name, trackNumber, duration, cdId FROM track";

if ($searchQuery !== "")
	$qtext .= " WHERE name LIKE :search";

$query = $DB->prepare($qtext);

if ($searchQuery !== "")
	$query->bindValue(":search", "%$searchQuery%", PDO::PARAM_STR);

$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) !== 0) { ?>
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
<?php } else if ($searchQuery !== "")
	echo "<p>No tracks found matching your search.</p>";
else
	echo "<p>No tracks found in the database.</p>";
