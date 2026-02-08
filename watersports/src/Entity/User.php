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

	// final public function eraseCredentials(): void
	// {
	// 	// If you store any temporary, sensitive data on the user, clear it here
	// 	// $this->plainPassword = null;
	// }

	// /**
	//  * Support session serialization
	//  */
	// final public function __serialize(): array
	// {
	// 	return [
	// 		'id' => $this->id,
	// 		'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
	// 		'username' => $this->username,
	// 		'password' => $this->password,
	// 	];
	// }

	// /**
	//  * Support session deserialization
	//  */
	// final public function __unserialize(array $data): void
	// {
	// 	$this->id = $data['id'];
	// 	$this->createdAt = new DateTime($data['createdAt']);
	// 	$this->username = $data['username'];
	// 	$this->password = $data['password'];
	// }
}
