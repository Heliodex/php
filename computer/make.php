<?php
session_start();

$make = $_GET['make'];
?>

<h1>COMPUTER SALES</h1>
<h2>SCOTLAND WIDE!</h2>

<p>
	Computer type selected:
	<?php echo $_SESSION['type']; ?>
</p>
<p>
	Computer make selected:
	<?php echo $make ?>
</p>

<form action="/computer/final.php">
	Select computer model
	<select name="model">
		<option value="CL2">CL2</option>
		<option value="R456">R456</option>
	</select>
	<br />
	<br />
	<input type="submit" value="Submit selection">
</form>