<?php
declare(strict_types=1);

namespace App\Service;

use App\Utils\FileLoader\FileLoaderInterface;
use Psr\Log\LoggerInterface;

class FoodCollectionProcessor
{
    private FileLoaderInterface $fileLoader;

    private FoodCollectionServiceInterface $foodCollectionService;

    private LoggerInterface $logger;

    public function __construct(
        FileLoaderInterface $fileLoader,
        FoodCollectionServiceInterface $foodCollectionService,
        LoggerInterface $logger
    ) {
        $this->fileLoader = $fileLoader;
        $this->foodCollectionService = $foodCollectionService;
        $this->logger = $logger;
    }

    public function process(string $filePath)
    {
        $this->logger->info('Processing food collection from file: ' . $filePath);

        $foodItems = $this->fileLoader->load($filePath);

        if (empty($foodItems)) {
            $this->logger->warning('No food items found in the file.');
            return;
        }

        [$fruitCollection, $vegetableCollection] = $this->foodCollectionService->extractCollections($foodItems);

        try {
            $this->foodCollectionService->saveCollection($fruitCollection);

            $this->foodCollectionService->saveCollection($vegetableCollection);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }

        $this->logger->info('Food collections processed successfully.'); 
    }
}