<?php

namespace App\Form\Model;

use App\Entity\Isbn;

class IsbnDto
{
    public ?string $isbn = null;
    public ?string $isbnLong = null;

    public static function createFromIsbn(Isbn $isbn): self
    {
        $dto = new self();
        $dto->isbn = $isbn->getIsbn();
        $dto->isbnLong = $isbn->getIsbnLong();
        return $dto;
    }
}
