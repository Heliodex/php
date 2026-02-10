<?php

namespace App\Form\Type;

use App\Entity\MemberDetails;
use Symfony\Component\Form\Extension\Core\Type\{EnumType, TextType};
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
enum MemberCategory
{
	case Gold;
	case Silver;
	case Bronze;
}

final class MemberDetailsType extends AbstractType
{
	final public function buildForm(FormBuilderInterface $builder, array $_): void
	{
		$builder
			->add("forename", TextType::class)
			->add("surname", TextType::class)
			->add("street", TextType::class)
			->add("town", TextType::class)
			->add("postcode", TextType::class)
			->add("memberCategory", EnumType::class, [
				"class" => MemberCategory::class,
			]);
	}

	final public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => MemberDetails::class,
		]);
	}
}
