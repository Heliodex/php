<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/form.php";
require_once "lib/page.php";

$_ = new Page("Home");
?>

<h1>Home</h1>

<h2>Search CDs</h2>

<div class="bottomgap">
	<form class="table inline-form">
		<label for="search">Search for CDs by title, label, or artist:</label>
		<fieldset>
			<input
				type="text"
				id="search"
				name="search"
				minlength="1"
				maxlength="100"
				hx-get="/cdQuery.php"
				hx-trigger="input changed, keyup[key=='Enter']"
				hx-target="#results"
				hx-indicator="#indicator">
			<button>Search</button>
		</fieldset>
	</form>
</div>

<h2>CDs in Database</h2>

<div id="results">
	<?php require "cdQuery.php"; ?>
</div>