<?php

namespace App\Form\Type;

use App\Entity\Login;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, SubmitType, TextType};
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LoginType extends AbstractType
{
	final public function buildForm(FormBuilderInterface $builder, array $_): void
	{
		$builder
			->add("email", TextType::class)
			->add("password", PasswordType::class)
			->add("submit", SubmitType::class, [
				"label" => "Log in",
			]);
	}

	final public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => Login::class,
		]);
	}
}
