<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Register
{
	#[Assert\NotBlank]
	final public string $username;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $email;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $password;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $confirmPassword;
}
