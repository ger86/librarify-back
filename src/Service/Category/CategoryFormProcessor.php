<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFormProcessor
{

    private GetCategory $getCategory;
    private CategoryRepository $categoryRepository;
    private FormFactoryInterface $formFactory;

    public function __construct(
        GetCategory $getCategory,
        CategoryRepository $categoryRepository,
        FormFactoryInterface $formFactory
    ) {
        $this->getCategory = $getCategory;
        $this->categoryRepository = $categoryRepository;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, ?string $categoryId = null): array
    {
        $category = null;
        $categoryDto = null;

        if ($categoryId === null) {
            $categoryDto = new CategoryDto();
        } else {
            $category = ($this->getCategory)($categoryId);
            $categoryDto = CategoryDto::createFromCategory($category);
        }
        
        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        if ($category === null) {
            $category = Category::create(
                $categoryDto->getName()
            );
        } else {
            $category->update(
                $categoryDto->getName()
            );
        }

        $this->categoryRepository->save($category);
        return [$category, null];
    }
}
