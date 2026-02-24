<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

 final class UpdatePassword
{
	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $currentPassword;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $newPassword;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1)]
	final public string $confirmPassword;
}
