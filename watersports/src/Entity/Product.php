<?php

namespace App\Entity;

use DateTime;

readonly final class Product
{
	final public string $id;
	final public DateTime $created;
	final public string $name;
	final public ?string $description;
	final public int $price;

	final public function __construct(string $id, DateTime $created, string $name, ?string $description, int $price)
	{
		$this->id = $id;
		$this->created = $created;
		$this->name = $name;
		$this->description = $description;
		$this->price = $price;
	}
}
