<?php

declare(strict_types=1);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST["username"] ?? "";
	$password = $_POST["password"] ?? "";

	if (empty($username))
		$errors["username"] = "Username is required";
	elseif (strlen($username) < 3)
		$errors["username"] = "Username must be at least 3 characters long";

	if (empty($password))
		$errors["password"] = "Password is required";
	elseif (strlen($password) < 3)
		$errors["password"] = "Password must be at least 3 characters long";

	if (empty($errors)) {
		echo "Processing login...";
	}
}

require_once "lib/page.php";

$_ = new Page("Log in");
?>

<h1>Log in</h1>

<form method="post">
	<fieldset>
		<label for="username">Username</label>
		<input type="text" id="username" name="username" required>
		<?php
		$eusername = $errors["username"] ?? null; // bruh
		if (isset($eusername))
			echo "<small class=\"formerror\">$eusername</small>";
		?>
	</fieldset>

	<fieldset>
		<label for="password">Password</label>
		<input type="password" id="password" name="password" required>
		<?php
		$epassword = $errors["password"] ?? null;
		if (isset($epassword))
			echo "<small class=\"formerror\">$epassword</small>";
		?>
	</fieldset>

	<button>Log in</button>
</form>