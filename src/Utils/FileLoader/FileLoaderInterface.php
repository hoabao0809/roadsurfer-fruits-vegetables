<?php
declare(strict_types=1);

namespace App\Utils\FileLoader;

interface FileLoaderInterface
{
    public function load(string $filePath): array;
}