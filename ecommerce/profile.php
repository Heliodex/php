<?php

declare(strict_types=1);

require_once "lib/session.php";
new Session(true);

require_once "lib/database.php";
require_once "lib/page.php";

$_ = new Page("Profile");

$query = $DB->prepare("SELECT id, username, email FROM user WHERE id = ?");
$query->execute([$_SESSION["user"]]);
$row = $query->fetch(PDO::FETCH_ASSOC);
?>

<h1>Profile</h1>

<?php if ($row !== false) { ?>
	<table>
		<tbody>
			<?php foreach ($row as $key => $value) { ?>
				<tr>
					<th><?= htmlspecialchars($key) ?></th>
					<td><?= htmlspecialchars((string)$value) ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else
	echo "<p>No users found in the database. (how are you here? this requires you to be logged in to see this page...)</p>";
