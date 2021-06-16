<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Repository\AuthorRepository;

class CreateAuthor
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(string $name): ?Author
    {
        $author = Author::create($name);
        $this->authorRepository->save($author);
        return $author;
    }
}
