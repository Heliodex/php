<?php

namespace App\Form\Type;

use App\Entity\Register;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, PasswordType, SubmitType, TextType};
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
	final public function buildForm(FormBuilderInterface $builder, array $_): void
	{
		$builder
			->add("username", TextType::class)
			->add("email", EmailType::class)
			->add("password", PasswordType::class)
			->add("confirmPassword", PasswordType::class)
			->add("submit", SubmitType::class);
	}

	final public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => Register::class,
		]);
	}
}
