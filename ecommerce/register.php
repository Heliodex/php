<?php

declare(strict_types=1);

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
	->maxLength(6969);

$form = new Form($_SERVER["REQUEST_METHOD"], $_POST, [$usernameRule, $passwordRule], function () {
	if ($_POST["username"] == "kyle")
		return ["username" => "You are banned"];

	echo "Login successful!";
});

require_once "lib/page.php";

$_ = new Page("Register");
?>

<h1>Register</h1>

<form method="post">
	<fieldset>
		<?= $usernameRule->input() ?>
		<?= $form->errorNotification("username") ?>
	</fieldset>

	<fieldset>
		<?= $emailRule->input("email") ?>
		<?= $form->errorNotification("email") ?>
	</fieldset>

	<fieldset>
		<?= $passwordRule->input("password") ?>
		<?= $form->errorNotification("password") ?>
	</fieldset>

	<button>Log in</button>
</form>