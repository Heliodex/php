<?php
session_start();

$_SESSION['type'] = $_GET['type'];
?>

<h1>COMPUTER SALES</h1>
<h2>SCOTLAND WIDE!</h2>

<p>
	Computer type selected:
	<?php echo $_SESSION['type']; ?>
</p>

<form action="/computer/make.php">
	Select computer make
	<select name="make">
		<option value="Cell">Cell</option>
		<option value="Race">Race</option>
		<option value="Sung">Sung</option>
	</select>
	<br />
	<br />
	<input type="submit" value="Submit selection">
</form>