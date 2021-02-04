<?php

namespace App\Service\Author;

use App\Repository\AuthorRepository;

class DeleteAuthor
{
    private GetAuthor $getAuthor;
    private AuthorRepository $authorRepository;

    public function __construct(GetAuthor $getAuthor, AuthorRepository $authorRepository)
    {
        $this->getAuthor = $getAuthor;
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(string $id)
    {
        $author = ($this->getAuthor)($id);
        $this->authorRepository->delete($author);
    }
}
