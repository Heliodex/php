<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Tracks");
?>

<h1>Tracks</h1>

<h2>Search tracks</h2>

<div class="bottomgap">
	<form class="table inline-form">
		<label for="search">Search for tracks by name</label>
		<fieldset>
			<input
				type="text"
				id="search"
				name="search"
				minlength="1"
				maxlength="100"
				hx-get="/tracksQuery.php"
				hx-trigger="input changed"
				hx-target="#results">
			<button>Search</button>
		</fieldset>
	</form>
</div>


<h2>Tracks in database</h2>

<div id="results">
	<?php require "tracksQuery.php"; ?>
</div>