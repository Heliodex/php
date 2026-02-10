<?php

namespace App\Entity;

use DateTime;

readonly final class User
{
	final public string $id;
	final public DateTime $createdAt;
	final public string $email;
	final public string $password;

	final public function __construct(string $id, DateTime $createdAt, string $email, string $password)
	{
		$this->id = $id;
		$this->createdAt = $createdAt;
		$this->email = $email;
		$this->password = $password;
	}
}
