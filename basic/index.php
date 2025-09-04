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
		$arr = [7, 6, 4, "hlello", 5];

		for ($i = 0; $i <= count($arr) - 1; $i++)
			echo $arr[$i] . ", ";

		echo "<br />";

		$j = 0;
		do {
			echo $j;

			switch ($j) {
				case 0:
					echo "zero";
				case 1:
					echo "one";
				case 2:
					echo "two";
				case 3:
					echo "three";
				case 4:
					echo "four";
				case 5:
					echo "five";
			}


			$j++;
			echo "<br />";
		} while ($j <= 5);

		// echo $_SERVER['HTTP_USER_AGENT'];
		?>

		<form action="login.php" method="POST">
			<div class="mb-3">
				<label for="name" class="form-label">Name</label>
				<input class="form-control" id="name" name="name" />
			</div>
			<button class="btn btn-primary">Submit</button>
		</form>
	</div>

</body>

</html>