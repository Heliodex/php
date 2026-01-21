<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(false);

require_once "lib/form.php";

$usernameRule = new Rule("Username")
	->required()
	->minLength(3)
	->maxLength(21);
$emailRule = new Rule("Email")
	->required()
	->email();
$passwordRule = new Rule("Password")
	->required()
	->minLength(3)
	->maxLength(6969)
	->password();

$form = new Form("register", $_SERVER["REQUEST_METHOD"], $_GET, $_POST, [
	$usernameRule,
	$emailRule,
	$passwordRule
], function () {
	if ($_POST["username"] == "kyle")
		return ["username" => "You are banned"];

	$hash = password_hash($_POST["password"], PASSWORD_ARGON2ID);

	require_once "lib/database.php";

	$query = $DB->prepare(
		"INSERT INTO user (username, email, password) VALUES (:username, :email, :password);
		SELECT id FROM user WHERE username = :username;"
	);
	$query->execute([
		":username" => $_POST["username"],
		":email" => $_POST["email"],
		":password" => $hash,
	]);

	$query->nextRowset();
	$user = $query->fetch(PDO::FETCH_ASSOC);
	$_SESSION["user"] = $user["id"];
});

require_once "lib/page.php";

$_ = new Page("Register");
?>

<h1>Register</h1>

<form method="post" action="?/register">
	<fieldset>
		<?= $usernameRule->input($_POST) ?>
		<?= $form->errorNotification("username") ?>
	</fieldset>

	<fieldset>
		<?= $emailRule->input($_POST) ?>
		<?= $form->errorNotification("email") ?>
	</fieldset>

	<fieldset>
		<?= $passwordRule->input($_POST) ?>
		<?= $form->errorNotification("password") ?>
	</fieldset>

	<button class="smallbtn">Register</button>
</form>