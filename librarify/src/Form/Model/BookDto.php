<?php

namespace App\Form\Model;

use App\Entity\Book;

class BookDto
{
    public ?string $title = null;
    public ?string $base64Image = null;
    /** @var \App\Form\Model\CategoryDto[]|null */
    public ?array $categories = [];

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        return $dto;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBase64Image(): ?string
    {
        return $this->base64Image;
    }

    /**
     * @return \App\Form\Model\CategoryDto[]|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }
}
