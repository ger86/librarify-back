<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;
use App\Service\Utils\Security;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAuthor
{
    public function __construct(private AuthorRepository $authorRepository, private Security $security)
    {
    }

    public function __invoke(string $id): ?Author
    {
        $author = $this->authorRepository->find(Uuid::fromString($id));
        if (!$author) {
            AuthorNotFound::throwException();
        }
        $user = $this->security->getCurrentUser();
        if ($author->getUser() !== $user) {
            throw new AccessDeniedException('Author not visible');
        }
        return $author;
    }
}
