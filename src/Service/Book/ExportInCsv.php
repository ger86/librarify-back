<?php

namespace App\Service\Book;

use App\Repository\BookRepository;
use App\Service\Utils\WriteCsvInterface;

class ExportInCsv
{
    private BookRepository $bookRepository;
    private WriteCsvInterface $writeCsv;
    private string $projectFolder;

    public function __construct(
        BookRepository $bookRepository,
        WriteCsvInterface $writeCsv,
        string $projectFolder
    ) {
        $this->bookRepository = $bookRepository;
        $this->writeCsv = $writeCsv;
        $this->projectFolder = $projectFolder;
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
        $this->writeCsv->write($contents, $this->projectFolder .'/var/books.csv', $headers);
    }
}