<?php

namespace App\Controller;

use App\Database;
use App\Entity\UpdatePassword;
use App\Form\Type\UpdatePasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends Base
{
	#[Route("/profile", name: "profile")]
	final public function home(Request $request): Response
	{
		$user = $this->user($request);
		if (!$user)
			return $this->redirectToRoute("login");

		$updatePassword = new UpdatePassword();
		$form = $this->createForm(UpdatePasswordType::class, $updatePassword);

		$finish = fn() => $this->finish($request, "profile.html.twig", [
			"form" => $form,
		]);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			$currentPassword = $data->currentPassword;
			$newPassword = $data->newPassword;
			$confirmPassword = $data->confirmPassword;

			if ($newPassword !== $confirmPassword) {
				$form->addError(new FormError("Passwords do not match"));
				return $finish();
			}

			if (!Database::checkUser($user->email, $currentPassword)) {
				$form->addError(new FormError("Current password is incorrect"));
				return $finish();
			}

			if (!Database::updatePassword($user->id, $newPassword)) {
				$form->addError(new FormError("Failed to update password"));
				return $finish();
			}
		}

		return $finish();
	}
}
