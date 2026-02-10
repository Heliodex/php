<?php

namespace App\Form\Type;

use App\Entity\Register;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, PasswordType, SubmitType};
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegisterType extends AbstractType
{
	final public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		// Compose forms
		$memberDetails = new MemberDetailsType();
		$memberDetails->buildForm($builder, $options);

		$builder
			->add("email", EmailType::class)
			->add("password", PasswordType::class)
			->add("confirmPassword", PasswordType::class)
			->add("submit", SubmitType::class, [
				"label" => "Register",
			]);
	}

	final public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => Register::class,
		]);
	}
}
