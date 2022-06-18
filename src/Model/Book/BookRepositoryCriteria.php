<?php

namespace App\Model\Book;

use Ramsey\Uuid\UuidInterface;

class BookRepositoryCriteria
{
    public function __construct(
        public readonly ?string $authorId = null,
        public readonly ?string $categoryId = null,
        public readonly ?string $searchText = null,
        public readonly int $itemsPerPage = 10,
        public readonly int $page = 1,
        public readonly UuidInterface $userId
    ) {
    }
}
