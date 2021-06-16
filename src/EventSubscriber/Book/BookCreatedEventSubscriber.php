<?php

namespace App\EventSubscriber\Book;

use App\Event\Book\BookCreatedEvent;
use App\Service\Book\GetBook;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class BookCreatedEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private GetBook $getBook;

    public function __construct(GetBook $getBook, LoggerInterface $logger)
    {
        $this->getBook = $getBook;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BookCreatedEvent::class => ['onBookCreated']
        ];
    }

    public function onBookCreated(BookCreatedEvent $event)
    {
        $book = ($this->getBook)($event->getBookId()->toString());
        $this->logger->info(sprintf('Book created: %s', $book->getTitle()));
    }
}
