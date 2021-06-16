<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Model\Exception\Category\CategoryNotFound;
use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;

class GetCategory
{

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $id): ?Category
    {
        $category = $this->categoryRepository->find(Uuid::fromString($id));
        if (!$category) {
            CategoryNotFound::throwException();
        }
        return $category;
    }
}
