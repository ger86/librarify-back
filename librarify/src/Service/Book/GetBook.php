<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use Ramsey\Uuid\Nonstandard\Uuid;

class GetBook
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $id): Book
    {
        $book = $this->bookRepository->find(Uuid::fromString($id)); 
        if (!$book) {
            BookNotFound::throwException();
        }
        return $book;
    }
}