<?php

namespace App\Form\Type;

use App\Entity\UpdatePassword;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, SubmitType};
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UpdatePasswordType extends AbstractType
{
	final public function buildForm(FormBuilderInterface $builder, array $_): void
	{
		$builder
			->add("currentPassword", PasswordType::class)
			->add("newPassword", PasswordType::class)
			->add("confirmPassword", PasswordType::class)
			->add("submit", SubmitType::class, [
				"label" => "Update",
			]);
	}

	final public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => UpdatePassword::class,
		]);
	}
}
