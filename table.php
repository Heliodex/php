<?php
require("database.php");

$result = mysqli_query($db, "SELECT * FROM phptab");
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
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Id</th>
					<th scope="col">Name</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = mysqli_fetch_array($result)) { ?>
					<tr>
						<th>
							<?php echo $row["id"] ?>
						</th>
						<td>
							<?php echo $row["name"] ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>

</html>