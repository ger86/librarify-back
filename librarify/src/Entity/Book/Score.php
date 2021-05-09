<?php

namespace App\Entity\Book;

use App\Model\ValueObjectInterface;
use InvalidArgumentException;

class Score implements ValueObjectInterface
{
    public ?int $value = null;

    public function __construct(?int $value = null)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public static function create(?int $value = null): self
    {;
        return new self($value);
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    private function assertValueIsValid(?int $value) {
        if ($value === null) {
            return null;
        }
        if ($value > 5 || $value < 0) {
            throw new InvalidArgumentException('El score tiene que estar entre 0 y 5');
        }
    }
}
