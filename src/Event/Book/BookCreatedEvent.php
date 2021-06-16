<?php

namespace App\Event\Book;

use Symfony\Contracts\EventDispatcher\Event;
use Ramsey\Uuid\UuidInterface;

class BookCreatedEvent extends Event
{
    public const NAME = 'book.created';

    private UuidInterface $bookId;

    public function __construct(UuidInterface $bookId)
    {
        $this->bookId = $bookId;
    }

    public function getBookId(): UuidInterface
    {
        return $this->bookId;
    }
}
