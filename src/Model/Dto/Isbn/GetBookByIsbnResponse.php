<?php

namespace App\Model\Dto\Isbn;

class GetBookByIsbnResponse {
    private int $numberOfPages;
    private string $title;
    private string $publishDate;

    public function __construct(
        string $title,
        int $numberOfPages,
        string $publishDate
    )
    {
        $this->title = $title;
        $this->numberOfPages = $numberOfPages;
        $this->publishDate = $publishDate;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPublishDate(): string
    {
        return $this->publishDate;
    }
}