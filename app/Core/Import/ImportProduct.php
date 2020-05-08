<?php

namespace App\Core\Import;

class ImportProduct
{
    private $importProvider;

    public function __construct(ImportProvider $importProvider)
    {
        $this->importProvider = $importProvider;
    }

    public function save(string $filePath): array
    {
        return $this->importProvider->getDataFromFile($filePath);
    }
}