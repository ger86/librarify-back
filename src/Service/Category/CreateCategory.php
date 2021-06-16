<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CreateCategory
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $name): ?Category
    {
        $category = Category::create($name);
        $this->categoryRepository->save($category);
        return $category;
    }
}