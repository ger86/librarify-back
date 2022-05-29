<?php

namespace App\Controller\Api;

use App\Repository\AuthorRepository;
use App\Service\Author\AuthorFormProcessor;
use App\Service\Author\DeleteAuthor;
use App\Service\Author\GetAuthor;
use App\Service\Utils\Security;
use Exception;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthorController extends AbstractFOSRestController
{

    #[Get(path: "/authors")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function getAction(AuthorRepository $authorRepository, Security $security)
    {
        $user = $security->getCurrentUser();
        return $authorRepository->findBy(['user' => $user]);
    }

    #[Get(path: "/authors/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function getSingleAction(string $id, GetAuthor $getAuthor, Security $security)
    {
        try {
            $author = ($getAuthor)($id);
        } catch (Exception) {
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
        return $author;
    }

    #[Post(path: "/authors")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function postAction(
        Request $request,
        AuthorFormProcessor $authorFormProcessor
    ) {
        [$author, $error] = ($authorFormProcessor)($request);
        $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $author ?? $error;
        return View::create($data, $statusCode);
    }

    #[Put(path: "/authors/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
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
        } catch (Throwable) {
            return View::create('Author not found', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Delete(path: "/authors/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function deleteAction(
        string $id,
        DeleteAuthor $deleteAuthor,
        GetAuthor $getAuthor
    ) {
        ($getAuthor)($id);
        try {
            ($deleteAuthor)($id);
        } catch (Throwable) {
            return View::create('Author not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
