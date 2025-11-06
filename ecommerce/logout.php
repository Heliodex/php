<?php

require_once "lib/session.php";

new Session(true);

if (isset($_SESSION["user"]))
	unset($_SESSION["user"]);

header("Location: /login.php");
