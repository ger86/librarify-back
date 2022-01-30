<?php

namespace App\Service\Author;

use App\Repository\AuthorRepository;

class DeleteAuthor
{

    public function __construct(
        private GetAuthor $getAuthor,
        private AuthorRepository $authorRepository
    ) {
    }

    public function __invoke(string $id): void
    {
        $author = ($this->getAuthor)($id);
        $this->authorRepository->delete($author);
    }
}
