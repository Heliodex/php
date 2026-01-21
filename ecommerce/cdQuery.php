<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

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

if (count($rows) !== 0) { ?>
	<table>
		<colgroup>
			<col style="width: 15%">
			<col style="width: 35%">
			<col style="width: 10%">
			<col style="width: 20%">
			<col style="width: 20%">
		</colgroup>

		<thead>
			<tr>
				<th>ID</th>
				<th>Title, Label</th>
				<th>Year</th>
				<th>Artist</th>
				<th>Price</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$hid = htmlspecialchars($row["id"]);
				$shortId = substr($hid, 0, 6);
			?>
				<tr>
					<td><a href="cd.php?id=<?= urlencode($hid) ?>"><?= $shortId ?></a></td>
					<td>
						<?= htmlspecialchars($row["title"]) ?>
						<br>
						<?= htmlspecialchars($row["label"]) ?>
					</td>
					<td><?= htmlspecialchars((string)$row["year"]) ?></td>
					<td><?= htmlspecialchars($row["artist"]) ?></td>
					<td>
						<div style="display: flex; align-items: center; justify-content: space-between;">
							<?= htmlspecialchars(number_format((float)$row["price"], 2)) ?>
							<form method="post" action="?/add" class="inline-form">
								<input type="hidden" name="id" value="<?= $hid ?>">
								<button>
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="8" cy="21" r="1" />
										<circle cx="19" cy="21" r="1" />
										<path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
									</svg>
								</button>
							</form>
						</div>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else if ($searchQuery !== "")
	echo "<p>No CDs found matching your search.</p>";
else
	echo "<p>No CDs found in the database.</p>";
