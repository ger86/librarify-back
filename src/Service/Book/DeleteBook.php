<?php

namespace App\Service\Book;

use App\Repository\BookRepository;

class DeleteBook
{
    public function __construct(
        private GetBook $getBook,
        private BookRepository $bookRepository
    ) {
    }

    public function __invoke(string $id): void
    {
        $book = ($this->getBook)($id);
        $this->bookRepository->delete($book);
    }
}
