<?php

namespace App\Service\Book;

use App\Repository\BookRepository;
use App\Service\Utils\WriteCsvInterface;

class ExportInCsv
{
    public function __construct(
        private BookRepository $bookRepository,
        private WriteCsvInterface $writeCsv,
        private string $projectFolder
    ) {
    }

    public function __invoke(): void
    {
        $books = $this->bookRepository->findAll();
        $headers = [
            'id',
            'title'
        ];
        $contents = [];
        foreach ($books as $book) {
            $contents[] = [
                $book->getId()->toString(),
                $book->getTitle()
            ];
        }
        $this->writeCsv->write($contents, $this->projectFolder . '/var/books.csv', $headers);
    }
}
