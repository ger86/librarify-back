<?php

namespace App\Event\Book;

use Symfony\Contracts\EventDispatcher\Event;
use Ramsey\Uuid\UuidInterface;

class BookCreatedEvent extends Event
{
    public const NAME = 'book.created';


    public function __construct(public readonly UuidInterface $bookId)
    {
    }
}
