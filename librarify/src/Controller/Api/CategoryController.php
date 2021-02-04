<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Service\Category\CategoryFormProcessor;
use App\Service\Category\DeleteCategory;
use App\Service\Category\GetCategory;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(CategoryRepository $categoryRepository)
    {
        return $categoryRepository->findAll();
    }

    /**
     * @Rest\Get(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(string $id, GetCategory $getCategory)
    {
        try {
            $category = ($getCategory)($id);
        } catch (Exception $exception) {
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
        return $category;
    }


    /**
     * @Rest\Post(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        CategoryFormProcessor $categoryFormProcessor
    ) {
        [$category, $error] = ($categoryFormProcessor)($request);
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        CategoryFormProcessor $categoryFormProcessor,
        Request $request
    ) {
        try {
            [$category, $error] = ($categoryFormProcessor)($request, $id);
            $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $category ?? $error;
            return View::create($data, $statusCode);
        } catch (Throwable $t) {
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\Delete(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id, DeleteCategory $deleteCategory)
    {
        try {
            ($deleteCategory)($id);
        } catch (Throwable $t) {
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
