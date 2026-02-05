<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Login
{
	#[Assert\NotBlank]
	public string $username;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	public string $password;
}
