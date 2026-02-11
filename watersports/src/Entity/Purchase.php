<?php

namespace App\Entity;

use DateTime;

readonly final class Purchase
{
	final public string $id;
	final public DateTime $created;
	final public array $products;
	final public bool $completed;

	final public function __construct(string $id, DateTime $created, array $products, bool $completed)
	{
		$this->id = $id;
		$this->created = $created;
		$this->products = $products;
		$this->completed = $completed;
	}
}
