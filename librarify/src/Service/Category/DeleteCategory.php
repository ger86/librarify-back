<?php

namespace App\Service\Category;

use App\Repository\CategoryRepository;

class DeleteCategory
{
    private GetCategory $getCategory;
    private CategoryRepository $categoryRepository;

    public function __construct(GetCategory $getCategory, CategoryRepository $categoryRepository)
    {
        $this->getCategory = $getCategory;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $id)
    {
        $category = ($this->getCategory)($id);
        $this->categoryRepository->delete($category);
    }
}
