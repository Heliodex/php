<?php
session_start();
?>
<html>

<head>
	<title>Session Counter</title>
	<meta charset="UTF-8">
</head>

<body>
	<h1>Session Counter</h1>
	<?php
	//Check if the session variable has been set/created
	if (!isset($_SESSION['counter'])) {
		$_SESSION['counter'] = 1;
	} else {
		$_SESSION['counter']++;
	}
	echo "Press the refresh button to test! <br>";
	echo "You have visited this page " . $_SESSION['counter'] . " times <br>";
	?>