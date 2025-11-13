<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

$id = $_GET["id"] ?? null;
if ($id === null)
	require_once "lib/404.php";

require_once "lib/page.php";
require_once "lib/cdform.php";

$trackNameRule = new Rule("Track Name")
	->required()
	->maxLength(255);
$trackNumberRule = new Rule("Track Number")
	->required()
	->number()
	->minValue(1)
	->maxValue(99);
$trackDurationRule = new Rule("Track Duration")
	->required();

$form = new Form("update", $_SERVER["REQUEST_METHOD"], $_GET, $_POST, $fields, function () {
	require_once "lib/database.php";

	$query = $DB->prepare(
		"UPDATE cd SET
			title = :title,
			label = :label,
			year = :year,
			artist = :artist,
			price = :price
		WHERE id = :id"
	);
	$query->execute([
		":title" => $_POST["title"],
		":label" => $_POST["label"],
		":year" => $_POST["year"],
		":artist" => $_POST["artist"],
		":price" => $_POST["price"],
		":id" => $_GET["id"],
	]);

	header("Location: /cd.php?id=" . urlencode($_GET["id"]));
});

$trackForm = new Form("track", $_SERVER["REQUEST_METHOD"], $_GET, $_POST, [
	$trackNameRule,
	$trackNumberRule,
	$trackDurationRule
], function () {
	require_once "lib/database.php";

	$duration = DateTime::createFromFormat("i:s", $_POST["track_duration"]);
	if ($duration === false)
		return ["track_duration" => "Invalid time format, expected MM:SS"];

	$duration = $duration->format("H:i:s");

	$query = $DB->prepare(
		"INSERT INTO track (name, trackNumber, duration, cdId) VALUES (:name, :trackNumber, :duration, :cdId)"
	);

	$query->execute([
		":name" => $_POST["track_name"],
		":trackNumber" => $_POST["track_number"],
		":duration" => $duration,
		":cdId" => $_GET["id"],
	]);

	header("Location: /cd.php?id=" . urlencode($_GET["id"]));
});

require "lib/database.php";

// select CD and associated tracks
$query = $DB->prepare(
	"SELECT * FROM cd WHERE id = :id;
	SELECT * FROM track WHERE cdId = :id ORDER BY trackNumber ASC;"
);
$query->execute([
	":id" => $id
]);
$cd = $query->fetch(PDO::FETCH_ASSOC);
// $tracks = $query->fetchAll(PDO::FETCH_ASSOC)
$query->nextRowset();
$tracks = $query->fetchAll(PDO::FETCH_ASSOC);

if ($cd === false)
	require_once "lib/404.php";

$formpost = count($_POST) == 0 ? $cd : $_POST;

$_ = new Page("CD Details");
?>

<h1>CD details</h1>

<ul class="bottomgap">
	<?php foreach ($cd as $key => $value) echo "<li>$key: $value</li>"; ?>
</ul>

<h2>Tracks</h2>

<div class="bottomgap">
	<?php if (count($tracks) == 0) { ?>
		<p>No tracks found for this CD.</p>
	<?php } else { ?>
		<ul>
			<?php foreach ($tracks as $track) { ?>
				<li>
					<?= htmlspecialchars($track["trackNumber"] . ". " . $track["name"] . " (" . $track["duration"] . ")") ?>
				</li>
			<?php } ?>
		</ul>
	<?php } ?>
</div>

<h2>Update CD</h2>

<form method="post" action="?/update&id=<?= urlencode($cd["id"]) ?>" class="table">
	<fieldset>
		<?= $titleRule->input($formpost) ?>
		<?= $form->errorNotification("title") ?>
	</fieldset>

	<fieldset>
		<?= $labelRule->input($formpost) ?>
		<?= $form->errorNotification("label") ?>
	</fieldset>

	<fieldset>
		<?= $yearRule->input($formpost) ?>
		<?= $form->errorNotification("year") ?>
	</fieldset>

	<fieldset>
		<?= $artistRule->input($formpost) ?>
		<?= $form->errorNotification("artist") ?>
	</fieldset>

	<fieldset>
		<?= $priceRule->input($formpost) ?>
		<?= $form->errorNotification("price") ?>
	</fieldset>

	<button>Update CD</button>
</form>

<hr>

<h2>Add tracks</h2>

<form method="post" action="?/track&id=<?= urlencode($cd["id"]) ?>" class="table">
	<fieldset>
		<?= $trackNameRule->input($_POST) ?>
		<?= $trackForm->errorNotification("track_name") ?>
	</fieldset>

	<fieldset>
		<?= $trackNumberRule->input($_POST) ?>
		<?= $trackForm->errorNotification("track_number") ?>
	</fieldset>

	<fieldset>
		<?= $trackDurationRule->input($_POST) ?>
		<?= $trackForm->errorNotification("track_duration") ?>
	</fieldset>

	<button>Add Track</button>
</form>
