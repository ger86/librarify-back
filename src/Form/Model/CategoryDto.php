<?php

namespace App\Form\Model;

use App\Entity\Category;

class CategoryDto {
    public $id;
    public $name;

    public static function createFromCategory(Category $category): self
    {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();
        return $dto;
    }
}