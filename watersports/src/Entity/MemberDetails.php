<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/*
In order for customers to use the website they will require to register their details. This will involve the customer completing an online registration form which will contain:

Member Number. Automatically allocated.
Forename (maximum of 20 characters)
Surname (maximum of 20 characters)
Street  (maximum of 40 characters)
Town (maximum of 20 characters)
Postcode (maximum of 10 characters)
E-mail address (maximum of 40 characters)
Category of member (gold, silver or bronze) which will identify any discount on purchases
*/

class MemberDetails
{

	#[Assert\NotBlank]
	#[Assert\Length(min: 1, max: 20)]
	final public string $forename;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1, max: 20)]
	final public string $surname;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1, max: 40)]
	final public string $street;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1, max: 20)]
	final public string $town;

	#[Assert\NotBlank]
	#[Assert\Length(min: 1, max: 10)]
	final public string $postcode;

	#[Assert\NotBlank]
	final public string $memberCategory;
}
