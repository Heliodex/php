<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(false);

require_once "lib/form.php";

$usernameRule = new Rule("Username")
	->required()
	->minLength(3)
	->maxLength(21);
$passwordRule = new Rule("Password")
	->required()
	->minLength(3)
	->maxLength(6969)
	->password();

$form = new Form("login", $_SERVER["REQUEST_METHOD"], $_GET, $_POST, [
	$usernameRule,
	$passwordRule
], function () {
	if ($_POST["username"] == "kyle")
		return ["username" => "You are banned"];

	require_once "lib/database.php";

	$query = $DB->prepare("SELECT * FROM user WHERE username = :username");
	$query->execute([":username" => $_POST["username"]]);

	$user = $query->fetch(PDO::FETCH_ASSOC);
	if (!$user || !password_verify($_POST["password"], $user["password"]))
		return ["username" => "Invalid username or password"];

	$_SESSION["user"] = $user["id"];
	header("Location: home.php");
});

require_once "lib/page.php";

$_ = new Page("Log in");
?>

<h1>Log in</h1>

<form method="post" action="?/login">
	<fieldset>
		<?= $usernameRule->input($_POST) ?>
		<?= $form->errorNotification("username") ?>
	</fieldset>

	<fieldset>
		<?= $passwordRule->input($_POST) ?>
		<?= $form->errorNotification("password") ?>
	</fieldset>

	<button>Log in</button>
</form>