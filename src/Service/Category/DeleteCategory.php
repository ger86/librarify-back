<?php

namespace App\Service\Category;

use App\Repository\CategoryRepository;

class DeleteCategory
{

    public function __construct(
        private GetCategory $getCategory,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function __invoke(string $id): void
    {
        $category = ($this->getCategory)($id);
        $this->categoryRepository->delete($category);
    }
}
