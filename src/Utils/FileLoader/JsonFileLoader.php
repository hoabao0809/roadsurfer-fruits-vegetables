<?php
declare(strict_types=1);

namespace App\Utils\FileLoader;

class JsonFileLoader extends FileLoader
{
    protected function parse(string $content): array
    {
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON data: ' . json_last_error_msg());
        }

        return $data;
    }
}