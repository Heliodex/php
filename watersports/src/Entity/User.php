<?php

namespace App\Entity;

use DateTime;

final class User
{
	final public string $id;
	final public DateTime $createdAt;
	final public string $username;
	final public string $password;

	final public function __construct(string $id, DateTime $createdAt, string $username, string $password)
	{
		$this->id = $id;
		$this->createdAt = $createdAt;
		$this->username = $username;
		$this->password = $password;
	}
}
