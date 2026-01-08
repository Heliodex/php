<?php

declare(strict_types=1);

require_once "lib/page.php";

$_ = new Page("404 Not Found");
http_response_code(404);
?>

<h1>404 Not Found</h1>
<p>The page you requested could not be found.</p>

<?php
die;
