<?php

namespace App\Controller\Api;

use App\Model\Book\BookRepositoryCriteria;
use App\Repository\BookRepository;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use App\Service\Book\BookFormProcessor;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BooksController extends AbstractFOSRestController
{
    #[Get(path: "/books")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function getAction(
        BookRepository $bookRepository,
        Request $request
    ) {
        $authorId = $request->query->get('authorId');
        $categoryId = $request->query->get('categoryId');
        $page = $request->query->get('page');
        $itemsPerPage = $request->query->get('itemsPerPage');
        $criteria = new BookRepositoryCriteria(
            $authorId,
            $categoryId,
            $itemsPerPage !== null ? \intval($itemsPerPage) : 10,
            $page !== null ? \intval($page) : 1,
        );
        return $bookRepository->findByCriteria($criteria);
    }

    #[Get(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function getSingleAction(string $id, GetBook $getBook)
    {
        try {
            $book = ($getBook)($id);
        } catch (Exception) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return $book;
    }

    #[Post(path: "/books")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function postAction(
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        [$book, $error] = ($bookFormProcessor)($request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return View::create($data, $statusCode);
    }

    #[Put(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function editAction(
        string $id,
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        try {
            [$book, $error] = ($bookFormProcessor)($request, $id);
            $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $book ?? $error;
            return View::create($data, $statusCode);
        } catch (Throwable $e) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Patch(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function patchAction(
        string $id,
        GetBook $getBook,
        Request $request
    ) {
        $book = ($getBook)($id);
        $data = json_decode($request->getContent(), true);
        $book->patch($data);
        return View::create($book, Response::HTTP_OK);
    }

    #[Delete(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function deleteAction(string $id, DeleteBook $deleteBook)
    {
        try {
            ($deleteBook)($id);
        } catch (Throwable) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
