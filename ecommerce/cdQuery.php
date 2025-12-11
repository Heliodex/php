<?php

declare(strict_types=1);

require_once "lib/database.php";

$searchQuery = $_GET["search"] ?? "";

$qtext = "SELECT id, title, label, year, artist, price FROM cd";
if ($searchQuery !== "")
	$qtext .= " WHERE title LIKE :search OR label LIKE :search OR artist LIKE :search";

$query = $DB->prepare($qtext);

if ($searchQuery !== "")
	$query->bindValue(":search", "%$searchQuery%", PDO::PARAM_STR);

$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<?php if (count($rows) !== 0) { ?>
	<table>
		<colgroup>
			<col style="width: 15%">
			<col style="width: 20%">
			<col style="width: 25%">
			<col style="width: 10%">
			<col style="width: 20%">
			<col style="width: 10%">
		</colgroup>

		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Label</th>
				<th>Year</th>
				<th>Artist</th>
				<th>Price</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) { ?>
				<tr>
					<?php foreach ($row as $key => $value)
						if ($key === "id") {
							$shortId = substr(htmlspecialchars($value), 0, 6);
							echo "<td><a href=\"cd.php?id=" . urlencode(htmlspecialchars($value)) . "\">$shortId</a></td>";
						} else
							echo "<td>$value</td>"; ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else if ($searchQuery !== "")
	echo "<p>No CDs found matching your search.</p>";
else
	echo "<p>No CDs found in the database.</p>";
