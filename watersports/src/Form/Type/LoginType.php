<?php

namespace App\Form\Type;

use App\Entity\Login;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $_): void
	{
		$builder
			->add("username", TextType::class)
			->add("password", PasswordType::class)
			->add("submit", SubmitType::class);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => Login::class,
		]);
	}
}
