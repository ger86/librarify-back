<?php

namespace App\Model\Dto\Isbn;

class GetBookByIsbnResponse
{
    public function __construct(
        readonly public string $title,
        readonly public int $numberOfPages,
        readonly public string $publishDate
    ) {
    }
}
