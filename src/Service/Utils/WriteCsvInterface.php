<?php

namespace App\Service\Utils;

interface WriteCsvInterface
{
    public function write(iterable $elements, string $path, array $headers = null, string $mode = 'w+'): void;
}