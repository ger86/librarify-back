<?php

namespace App\Service;

use League\Flysystem\FilesystemInterface;

class FileUploader
{

    private $defaultStorage;

    public function __construct(FilesystemInterface $defaultStorage)
    {
        $this->defaultStorage = $defaultStorage;
    }

    public function uploadBase64File(string $base64File): string
    {
        $extension = explode('/', mime_content_type($base64File))[1];
        $data = explode(',', $base64File);
        $filename = sprintf('%s.%s', uniqid('book_', true), $extension);
        $this->defaultStorage->write($filename, base64_decode($data[1]));
        return $filename;
    }
}
