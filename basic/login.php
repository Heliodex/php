<?php
require "database.php";

$name = $_POST['name'];
$result = mysqli_query($db, "INSERT INTO phptab (name) VALUES ('$name')");
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
		You are
		<?php echo htmlspecialchars($_POST['name']) ?>
		<br />
		result
		<?php echo $result ?>
		<br />
		<a class="btn btn-primary" href="/table.php">table</a>
	</div>

</body>

</html>