<?php

namespace App\Entity;

use App\Entity\Book\Score;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Book
{
    private UuidInterface $id;

    private string $title;

    private ?string $image;

    /** @var Collection|Category[] */
    private Collection $categories;

    private Score $score;

    private ?string $description;

    private ?DateTimeInterface $createdAt;

    private ?DateTimeInterface $readAt;

    public function __construct(
        UuidInterface $uuid,
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        ?Collection $categories
    ) {
        $this->id = $uuid;
        $this->title = $title;
        $this->image = $image;
        $this->description = $description ?? $description;;
        $this->score = $score ?? Score::create();
        $this->readAt = $readAt;
        $this->categories = $categories ?? new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        Category ...$categories
    ): self {
        return new self(
            Uuid::uuid4(),
            $title,
            $image,
            $description,
            $score,
            $readAt,
            new ArrayCollection($categories)
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function updateCategories(Category ...$categories)
    {
        /** @var Category[]|ArrayCollection */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // Remove categories
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategory, $categories)) {
                $this->removeCategory($originalCategory);
            }
        }

        // Add categories
        foreach ($categories as $newCategory) {
            if (!$originalCategories->contains(!$newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    public function update(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        DateTimeInterface $readAt,
        Category ...$categories
    ) {
        $this->title = $title;
        $this->image = $image;
        $this->description = $description;
        $this->score = $score;
        $this->readAt = $readAt;
        $this->updateCategories(...$categories);
    }

    public function setScore(Score $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getScore(): Score
    {
        return $this->score;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isRead(): ?bool
    {
        return $this->readAt === null ? false : true;
    }

    public function markAsRead(DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;
        return $this;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }
}
