<?php

namespace App\Entity;

use DateTime;

readonly final class Purchase
{
	final public string $id;
	final public DateTime $created;
	final public Product $product;
	final public bool $completed;

	final public function __construct(string $id, DateTime $created, Product $product, bool $completed)
	{
		$this->id = $id;
		$this->created = $created;
		$this->product = $product;
		$this->completed = $completed;
	}
}
