<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/form.php";
require_once "lib/page.php";

$searchRule = new Rule("Search")
	->minLength(1)
	->maxLength(100);

require_once "lib/database.php";

$_ = new Page("Home");

$searchQuery = $_GET["search"] ?? "";

$qtext = "SELECT * FROM cd";
if ($searchQuery !== "")
	$qtext .= " WHERE title LIKE :search OR label LIKE :search OR artist LIKE :search";

$query = $DB->prepare($qtext);

if ($searchQuery !== "")
	$query->bindValue(":search", "%$searchQuery%", PDO::PARAM_STR);

$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Home</h1>

<h2>Search CDs</h2>
<form class="table bottomgap">
	<fieldset>
		<?= $searchRule->input($_GET) ?>
		<button class="smallbtn">Search</button>
	</fieldset>
</form>

<h2>CDs in Database</h2>

<?php if (count($rows) !== 0) { ?>
	<table>
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
