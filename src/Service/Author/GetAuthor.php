<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;
use Ramsey\Uuid\Uuid;

class GetAuthor
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function __invoke(string $id): ?Author
    {
        $author = $this->authorRepository->find(Uuid::fromString($id));
        if (!$author) {
            AuthorNotFound::throwException();
        }
        return $author;
    }
}
