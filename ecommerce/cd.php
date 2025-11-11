<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

$id = $_GET["id"] ?? null;
if ($id === null)
	require_once "lib/404.php";

require_once "lib/page.php";
require_once "lib/cdform.php";

$form = new Form($_SERVER["REQUEST_METHOD"], $_POST, $fields, function () {
	require_once "lib/database.php";

	$query = $DB->prepare(
		"UPDATE cd
		SET
			title = :title,
			label = :label,
			year = :year,
			artist = :artist,
			price = :price
		WHERE id = :id");
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

require_once "lib/database.php";

$query = $DB->prepare("SELECT * FROM cd WHERE id = ?");
$query->execute([$id]);
$cd = $query->fetch(PDO::FETCH_ASSOC);

if ($cd === false)
	require_once "lib/404.php";

$formpost = count($_POST) == 0 ? $cd : $_POST;

$_ = new Page("CD Details");
?>

<h1>CD Details</h1>

<ul>
	<?php foreach ($cd as $key => $value) echo "<li>$key: $value</li>"; ?>
</ul>
<hr>

<h2>Update CD</h2>

<form method="post" class="table">
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