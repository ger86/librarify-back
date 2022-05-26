<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Form\Model\AuthorDto;
use App\Form\Type\AuthorFormType;
use App\Repository\AuthorRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorFormProcessor
{
    public function __construct(
        private GetAuthor $getAuthor,
        private AuthorRepository $authorRepository,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function __invoke(Request $request, ?string $authorId = null): array
    {
        $author = null;
        $authorDto = null;

        if ($authorId === null) {
            $authorDto = new AuthorDto();
        } else {
            $author = ($this->getAuthor)($authorId);
            $authorDto = AuthorDto::createFromAuthor($author);
        }

        $form = $this->formFactory->create(AuthorFormType::class, $authorDto);
        $content = json_decode($request->getContent(), true);
        $form->submit($content);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        if ($author === null) {
            $author = Author::create(
                $authorDto->name
            );
        } else {
            $author->update(
                $authorDto->name
            );
        }

        $this->authorRepository->save($author);
        return [$author, null];
    }
}
