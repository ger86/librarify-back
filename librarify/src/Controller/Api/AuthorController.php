<?php

namespace App\Controller\Api;

use App\Repository\AuthorRepository;
use App\Service\Author\AuthorFormProcessor;
use App\Service\Author\DeleteAuthor;
use App\Service\Author\GetAuthor;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthorController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/authors")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(AuthorRepository $authorRepository)
    {
        return $authorRepository->findAll();
    }

    /**
     * @Rest\Get(path="/authors/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(string $id, GetAuthor $getAuthor)
    {
        try {
            $author = ($getAuthor)($id);
        } catch (Exception $exception) {
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
        return $author;
    }


    /**
     * @Rest\Post(path="/authors")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        AuthorFormProcessor $authorFormProcessor
    ) {
        [$author, $error] = ($authorFormProcessor)($request);
        $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $author ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/authors/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        AuthorFormProcessor $authorFormProcessor,
        Request $request
    ) {
        try {
            [$author, $error] = ($authorFormProcessor)($request, $id);
            $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $author ?? $error;
            return View::create($data, $statusCode);
        } catch (Throwable $t) {
            return View::create('Author not found', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\Delete(path="/authors/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id, DeleteAuthor $deleteAuthor)
    {
        try {
            ($deleteAuthor)($id);
        } catch (Throwable $t) {
            return View::create('Author not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
