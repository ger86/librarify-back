<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Repository\AuthorRepository;

class CreateAuthor
{

    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function __invoke(string $name): ?Author
    {
        $author = Author::create($name);
        $this->authorRepository->save($author);
        return $author;
    }
}
