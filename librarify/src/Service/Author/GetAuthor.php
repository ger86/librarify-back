<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Model\Exception\Category\CategoryNotFound;
use App\Repository\AuthorRepository;
use Ramsey\Uuid\Uuid;

class GetAuthor
{

    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(string $id): ?Author
    {
        $author = $this->authorRepository->find(Uuid::fromString($id));
        if (!$author) {
            CategoryNotFound::throwException();
        }
        return $author;
    }
}
