<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\Author\CreateAuthor;
use App\Service\Author\GetAuthor;
use App\Service\Category\CreateCategory;
use App\Service\Category\GetCategory;
use App\Service\FileUploader;
use App\Service\Utils\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookFormProcessor
{
    public function __construct(
        private GetBook $getBook,
        private BookRepository $bookRepository,
        private GetCategory $getCategory,
        private CreateCategory $createCategory,
        private CreateAuthor $createAuthor,
        private GetAuthor $getAuthor,
        private FileUploader $fileUploader,
        private FormFactoryInterface $formFactory,
        private EventDispatcherInterface $eventDispatcher,
        private Security $security
    ) {
    }

    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $user = $this->security->getCurrentUser();
        $book = null;
        $bookDto = null;

        if ($bookId === null) {
            $bookDto = BookDto::createEmpty();
        } else {
            $book = ($this->getBook)($bookId);
            $bookDto = BookDto::createFromBook($book);
        }

        $content = json_decode($request->getContent(), true);
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->submit($content);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        $categories = [];
        foreach ($bookDto->categories as $newCategoryDto) {
            $category = null;
            if ($newCategoryDto->id !== null) {
                $category = ($this->getCategory)($newCategoryDto->id);
            }
            if ($category === null) {
                $category = ($this->createCategory)($newCategoryDto->name, $user);
            }
            $categories[] = $category;
        }

        $authors = [];
        foreach ($bookDto->authors as $newAuthorDto) {
            $author = null;
            if ($newAuthorDto->id !== null) {
                $author = ($this->getAuthor)($newAuthorDto->id);
            }
            if ($author === null) {
                $author = ($this->createAuthor)($newAuthorDto->name, $user);
            }
            $authors[] = $author;
        }

        $filename = null;
        if ($bookDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }

        if ($book === null) {
            $book = Book::create(
                $bookDto->title,
                $user,
                $filename,
                $bookDto->description,
                Score::create($bookDto->score),
                $bookDto->readAt,
                $authors,
                $categories
            );
        } else {
            $book->update(
                $bookDto->title,
                $filename === null ? $book->getImage() : $filename,
                $bookDto->description,
                Score::create($bookDto->score),
                $bookDto->readAt,
                $authors,
                $categories
            );
        }
        $this->bookRepository->save($book);
        foreach ($book->pullDomainEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }
        return [$book, null];
    }
}
