<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BookManager
{

    private $em;
    private $bookRepository;

    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository)
    {
        $this->em = $em;
        $this->bookRepository = $bookRepository;
    }

    public function find(UuidInterface $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getRepository(): BookRepository
    {
        return $this->bookRepository;
    }

    public function create(): Book
    {
        $book = new Book(Uuid::uuid4());
        return $book;
    }

    public function save(Book $book): Book
    {
        $this->em->persist($book);
        $this->em->flush();
        return $book;
    }

    public function reload(Book $book): Book
    {
        $this->em->refresh($book);
        return $book;
    }

    public function delete(Book $book)
    {
        $this->em->remove($book);
        $this->em->flush();
    }
}
