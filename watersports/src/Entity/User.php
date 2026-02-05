<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
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

	final public function getRoles(): array
	{
		return ["ROLE_USER"];
	}

	final public function getUserIdentifier(): string
	{
		return $this->id;
	}

	final public function getPassword(): string
	{
		return $this->password;
	}
}
