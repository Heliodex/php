<?php
// AH CS 2023, question 10c
session_start();
?>

<h1>COMPUTER SALES</h1>
<h2>SCOTLAND WIDE!</h2>

<form action="/computer/type.php">
	Select computer type
	<select name="type">
		<option value="Desktop">Desktop</option>
		<option value="Laptop">Laptop</option>
		<option value="Tablet">Tablet</option>
	</select>
	<br />
	<br />
	<input type="submit" value="Submit selection">
</form>