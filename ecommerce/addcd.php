<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/form.php";
require_once "lib/cdform.php";

$form = new Form("add", $_SERVER["REQUEST_METHOD"], $_GET, $_POST, $fields, function () {
	require_once "lib/database.php";

	$query = $DB->prepare("INSERT INTO cd (title, label, year, artist, price) VALUES (:title, :label, :year, :artist, :price)");
	$query->execute([
		":title" => $_POST["title"],
		":label" => $_POST["label"],
		":year" => $_POST["year"],
		":artist" => $_POST["artist"],
		":price" => $_POST["price"],
	]);

	header("Location: /cd.php?title=" . urlencode($_POST["title"]));
});

require_once "lib/page.php";

$_ = new Page("Add CD");
?>

<h1>Add CD</h1>

<form method="post" action="?/add" class="table">
	<fieldset>
		<?= $titleRule->input($_POST) ?>
		<?= $form->errorNotification("title") ?>
	</fieldset>

	<fieldset>
		<?= $labelRule->input($_POST) ?>
		<?= $form->errorNotification("label") ?>
	</fieldset>

	<fieldset>
		<?= $yearRule->input($_POST) ?>
		<?= $form->errorNotification("year") ?>
	</fieldset>

	<fieldset>
		<?= $artistRule->input($_POST) ?>
		<?= $form->errorNotification("artist") ?>
	</fieldset>

	<fieldset>
		<?= $priceRule->input($_POST) ?>
		<?= $form->errorNotification("price") ?>
	</fieldset>

	<button>Add CD</button>
</form>