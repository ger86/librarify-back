<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use App\Service\Utils\Security;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class GetBook
{

    public function __construct(private BookRepository $bookRepository, private Security $security)
    {
    }

    public function __invoke(string $id): Book
    {
        $book = $this->bookRepository->find(Uuid::fromString($id));
        if (!$book) {
            BookNotFound::throwException();
        }
        $user = $this->security->getCurrentUser();
        if ($book->getUser() !== $user) {
            throw new AccessDeniedException('Book not visible');
        }
        return $book;
    }
}
