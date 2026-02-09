<?php

namespace App\Event;

use App\Entity\Comment;
use Symfony\Contracts\EventDispatcher\Event;

final class CommentCreatedEvent extends Event
{
	public function __construct(
		protected Comment $comment,
	) {
	}

	public function getComment(): Comment
	{
		return $this->comment;
	}
}
