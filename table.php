<?php
require("database.php");

$query = $db->query("SELECT * FROM phptab");
$result = $query->fetch_all(MYSQLI_ASSOC);
$db->close();

echo var_dump($result)
	?>

<!DOCTYPE html>
<html>

<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body data-bs-theme="dark">
	<nav class="navbar bg-body-secondary">
		<div class="container-fluid">
			<a class="navbar-brand" href="/">php.guide</a>
		</div>
	</nav>
	<div class="container">
		<?php
		$hi = "Var";

		$arr = [1, 2, 3, 4, 5, 6, 7];

		$arr1 = [
			"hello" => "world",
			"hi" => "lol"
		];

		$i = 1;

		?>

		<table class="table">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Id</th>
					<th scope="col">Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($result as $key => $value) { ?>
				<tr>
					<th scope="row">
							<?= $key ?>
					</th>
					<th>
							<?= $value["id"] ?>
					</th>
					<td>
							<?= $value["name"] ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>


	</div>
</body>

</html>