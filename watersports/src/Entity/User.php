<?php

namespace App\Entity;

use DateTime;

readonly final class User
{
	final public string $id;
	final public DateTime $created;
	private DateTime $createdAt; // must exist
	final public string $email;
	final public string $password;

	final public function __construct(string $id, DateTime $created, string $email, string $password)
	{
		$this->id = $id;
		$this->created = $created;
		$this->email = $email;
		$this->password = $password;
	}
}
