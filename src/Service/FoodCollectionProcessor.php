<?php
declare(strict_types=1);

namespace App\Service;

use App\Utils\FileLoader\FileLoaderInterface;

final class FoodCollectionProcessor
{
    private FileLoaderInterface $fileLoader;

    private FoodCollectionServiceInterface $foodCollectionService;

    public function __construct(
        FileLoaderInterface $fileLoader,
        FoodCollectionServiceInterface $foodCollectionService,
    ) {
        $this->fileLoader = $fileLoader;
        $this->foodCollectionService = $foodCollectionService;
    }

    public function process(string $filePath)
    {
        $foodItems = $this->fileLoader->load($filePath);

        if (empty($foodItems)) {
            return;
        }

        [$fruitCollection, $vegetableCollection] = $this->foodCollectionService->extractCollections($foodItems);

        $this->foodCollectionService->saveCollection($fruitCollection);

        $this->foodCollectionService->saveCollection($vegetableCollection);
    }
}