<?php

declare(strict_types=1);

require_once "lib/form.php";

$usernameRule = new Rule("Username")
	->required()
	->minLength(3)
	->maxLength(21);
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

$_ = new Page("Log in");
?>

<h1>Log in</h1>

<form method="post">
	<fieldset>
		<?= $usernameRule->input() ?>
		<?= $form->errorNotification("username") ?>
	</fieldset>

	<fieldset>
		<?= $passwordRule->input("password") ?>
		<?= $form->errorNotification("password") ?>
	</fieldset>

	<button>Log in</button>
</form>