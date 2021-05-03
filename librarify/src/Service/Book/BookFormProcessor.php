<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Event\Book\BookCreatedEvent;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\Author\CreateAuthor;
use App\Service\Author\GetAuthor;
use App\Service\Category\CreateCategory;
use App\Service\Category\GetCategory;
use App\Service\FileUploader;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class BookFormProcessor
{

    private GetBook $getBook;
    private BookRepository $bookRepository;
    private CreateCategory $createCategory;
    private GetCategory $getCategory;
    private CreateAuthor $createAuthor;
    private GetAuthor $getAuthor;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        GetBook $getBook,
        BookRepository $bookRepository,
        GetCategory $getCategory,
        CreateCategory $createCategory,
        CreateAuthor $createAuthor,
        GetAuthor $getAuthor,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->getBook = $getBook;
        $this->bookRepository = $bookRepository;
        $this->createCategory = $createCategory;
        $this->getCategory = $getCategory;
        $this->createAuthor = $createAuthor;
        $this->getAuthor = $getAuthor;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $book = null;
        $bookDto = null;

        if ($bookId === null) {
            $bookDto = BookDto::createEmpty();
        } else {
            $book = ($this->getBook)($bookId);
            $bookDto = BookDto::createFromBook($book);
            foreach ($book->getCategories() as $category) {
                $bookDto->categories[] = CategoryDto::createFromCategory($category);
            }
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
        foreach ($bookDto->getCategories() as $newCategoryDto) {
            $category = null;
            if ($newCategoryDto->getId() !== null) {
                $category = ($this->getCategory)($newCategoryDto->getId());
            }
            if ($category === null) {
                $category = ($this->createCategory)($newCategoryDto->getName());
            }
            $categories[] = $category;
        }

        $authors = [];
        foreach ($bookDto->getAuthors() as $newAuthorDto) {
            $author = null;
            if ($newAuthorDto->getId() !== null) {
                $author = ($this->getAuthor)($newAuthorDto->getId());
            }
            if ($author === null) {
                $author = ($this->createAuthor)($newAuthorDto->getName());
            }
            $authors[] = $author;
        }

        $filename = null;
        if ($bookDto->getBase64Image()) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }

        if ($book === null) {
            $book = Book::create(
                $bookDto->getTitle(),
                $filename,
                $bookDto->getDescription(),
                Score::create($bookDto->getScore()),
                $bookDto->getReadAt(),
                $authors,
                $categories
            );
        } else {
            $book->update(
                $bookDto->getTitle(),
                $filename === null ? $book->getImage() : $filename,
                $bookDto->getDescription(),
                Score::create($bookDto->getScore()),
                $bookDto->getReadAt(),
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
