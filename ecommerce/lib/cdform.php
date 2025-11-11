<?php

declare(strict_types=1);

require_once "lib/form.php";

$titleRule = new Rule("Title")
	->required()
	->maxLength(255);
$labelRule = new Rule("Label")
	->required()
	->maxLength(255);
$yearRule = new Rule("Year")
	->required()
	->number()
	->minValue(1900)
	->maxValue(2100);
$artistRule = new Rule("Artist")
	->required()
	->maxLength(255);
$priceRule = new Rule("Price")
	->required()
	->number()
	->minValue(0)
	->maxValue(1000)
	->decimal();

$fields = [
	$titleRule,
	$labelRule,
	$yearRule,
	$artistRule,
	$priceRule
];
