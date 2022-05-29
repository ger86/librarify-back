<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Entity\User;
use App\Repository\AuthorRepository;

class CreateAuthor
{

    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function __invoke(string $name, User $user): Author
    {
        $author = Author::create($name, $user);
        $this->authorRepository->save($author);
        return $author;
    }
}
