<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Users");

$query = $DB->query("SELECT id, username, email FROM user");
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Users</h1>

<?php if (count($rows) !== 0) { ?>
	<table>
		<thead>
			<tr>
				<?php foreach (array_keys($rows[0]) as $key) echo "<th>$key</th>"; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) { ?>
				<tr>
					<?php foreach ($row as $key => $value)
						echo "<td>$value</td>"; ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else
	echo "<p>No users found in the database. (how are you here? this requires you to be logged in to see this page...)</p>";
