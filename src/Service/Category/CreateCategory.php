<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;

class CreateCategory
{

    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function __invoke(string $name, User $user): Category
    {
        $category = Category::create($name, $user);
        $this->categoryRepository->save($category);
        return $category;
    }
}
