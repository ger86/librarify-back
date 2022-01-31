<?php

namespace App\Service;

use League\Flysystem\FilesystemInterface;

class FileDeleter
{

    public function __construct(private FilesystemInterface $defaultStorage)
    {
    }

    public function __invoke(string $path): void
    {
        $this->defaultStorage->delete($path);
    }
}
