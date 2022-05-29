<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Model\Exception\Category\CategoryNotFound;
use App\Repository\CategoryRepository;
use App\Service\Utils\Security;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetCategory
{

    public function __construct(private CategoryRepository $categoryRepository, private Security $security)
    {
    }

    public function __invoke(string $id): ?Category
    {
        $category = $this->categoryRepository->find(Uuid::fromString($id));
        if (!$category) {
            CategoryNotFound::throwException();
        }
        $user = $this->security->getCurrentUser();
        if ($category->getUser() !== $user) {
            throw new AccessDeniedException('Category not visible');
        }
        return $category;
    }
}
