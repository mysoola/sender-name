<?php

namespace App\Core\Import;

interface ImportProvider
{
    function getDataFromFile(string $filePath): array;
}