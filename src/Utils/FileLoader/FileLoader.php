<?php
declare(strict_types=1);

namespace App\Utils\FileLoader;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

abstract class FileLoader implements FileLoaderInterface
{
    public function load(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException("File not found: $filePath");
        }

        $fileContents = file_get_contents($filePath);
        return $this->parse($fileContents);
    }

    abstract protected function parse(string $content): array;
}
