<?php
declare(strict_types=1);

namespace App\Service;

use App\Collection\FoodCollectionInterface;

interface FoodCollectionServiceInterface
{
    public function extractCollections(array $foodItems): array;

    public function saveCollection(FoodCollectionInterface $collection): void;
}
