<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

final class Login
{
	#[Assert\NotBlank]
	final public string $username;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $password;
}
