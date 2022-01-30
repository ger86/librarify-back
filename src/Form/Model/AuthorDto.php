<?php

namespace App\Form\Model;

use App\Entity\Author;
use Ramsey\Uuid\UuidInterface;

class AuthorDto
{
    public function __construct(
        public ?UuidInterface $id = null,
        public ?string $name = null
    ) {
    }

    public static function createFromAuthor(Author $author): self
    {
        return new self($author->getId(), $author->getName());
    }
}
