<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

class Isbn
{
    private UuidInterface $id;

    private string $isbn;

    private string $isbnLong;

    private Book $book;

    public function __construct(UuidInterface $id, string $isbn, string $isbnLong, Book $book)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->isbnLong = $isbnLong;
        $this->book = $book;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getIsbnLong(): string
    {
        return $this->isbnLong;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function update(string $isbn, string $isbnLong): self
    {
        $this->isbn = $isbn;
        $this->isbnLong = $isbnLong;
        return $this;
    }
}
