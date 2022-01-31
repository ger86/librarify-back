<?php

namespace App\Model\Book;

class BookRepositoryCriteria
{
    public function __construct(
        public readonly ?string $authorId = null,
        public readonly ?string $categoryId = null,
        public readonly int $itemsPerPage = 10,
        public readonly int $page = 1
    ) {
    }
}
