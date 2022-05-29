<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Category
{

    /** @var Collection<int,Book> $books */
    private Collection $books;

    public function __construct(
        private UuidInterface $id,
        private string $name,
        private User $user
    ) {
        $this->books = new ArrayCollection();
    }

    public static function create(string $name, User $user): self
    {
        return new self(Uuid::uuid4(), $name, $user);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int,Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
            $book->removeCategory($this);
        }

        return $this;
    }

    public function update(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->name ?? 'Categoría';
    }
}
