<?php

namespace App\Service\Category;


use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFormProcessor
{

    private CreateCategory $createCategory;
    private FormFactoryInterface $formFactory;

    public function __construct(
        CreateCategory $createCategory,
        FormFactoryInterface $formFactory
    ) {
        $this->createCategory = $createCategory;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request): array
    {
        $categoryDto = new CategoryDto();
        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        $category = ($this->createCategory)($categoryDto->getName());
        return [null, $category];
    }
}
