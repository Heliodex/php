<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	final public string $id;
	final public DateTime $createdAt;
	final public string $username;
	final public string $password;

	final public static function new(string $id, DateTime $createdAt, string $username, string $password)
	{
		$user = new self();
		$user->id = $id;
		$user->createdAt = $createdAt;
		$user->username = $username;
		$user->password = $password;
		return $user;
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

	final public function eraseCredentials(): void
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	/**
	 * Support session serialization
	 */
	final public function __serialize(): array
	{
		return [
			'id' => $this->id,
			'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
			'username' => $this->username,
			'password' => $this->password,
		];
	}

	/**
	 * Support session deserialization
	 */
	final public function __unserialize(array $data): void
	{
		$this->id = $data['id'];
		$this->createdAt = new DateTime($data['createdAt']);
		$this->username = $data['username'];
		$this->password = $data['password'];
	}
}
