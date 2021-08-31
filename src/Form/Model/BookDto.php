<?php

namespace App\Form\Model;

use App\Entity\Book;
use DateTimeInterface;

class BookDto
{
    public ?string $title = null;
    public ?string $description = null;
    public ?int $score = null;
    public ?string $base64Image = null;
    /** @var \App\Form\Model\CategoryDto[]|null */
    public ?array $categories = [];
    /** @var \App\Form\Model\AuthorDto[]|null */
    public ?array $authors = [];
    public ?DateTimeInterface $readAt = null;
    public ?IsbnDto $isbn = null;

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
        $dto->score = $book->getScore()->getValue();
        $dto->description = $book->getDescription();
        $dto->readAt = $book->getReadAt();
        $dto->isbn = $book->getIsbn() === null ? null : IsbnDto::createFromIsbn($book->getIsbn($book->getIsbn()));
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

    /**
     * @return \App\Form\Model\AuthorDto[]|null
     */
    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }

    public function getIsbn(): ?IsbnDto
    {
        return $this->isbn;
    }
}
