<?php

namespace App\Service\Book;

use App\Repository\BookRepository;

class DeleteBook
{
    private GetBook $getBook;
    private BookRepository $bookRepository;

    public function __construct(GetBook $getBook, BookRepository $bookRepository)
    {
        $this->getBook = $getBook;
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $id)
    {
        $book = ($this->getBook)($id);
        $this->bookRepository->delete($book);
    }
}
