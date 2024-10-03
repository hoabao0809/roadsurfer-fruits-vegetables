<?php
declare(strict_types=1);

namespace App\Service;

use App\Collection\FoodCollectionInterface;
use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Dto\FoodDto;
use App\Entity\Food;
use App\Enum\FoodType;
use App\Enum\Unit;
use App\Repository\FoodRepositoryInterface;
use App\Utils\Converter\UnitConverter;

class FoodCollectionService implements FoodCollectionServiceInterface
{
    private FoodRepositoryInterface $foodRepository;

    public function __construct(FoodRepositoryInterface $foodRepository)
    {
        $this->foodRepository = $foodRepository;
    }

    public function extractCollections(array $foodItems): array
    {
        $fruitCollection = new FruitCollection();
        $vegetableCollection = new VegetableCollection();

        foreach ($foodItems as $item) {
            $item = $this->transformFoodData($item);

            $foodDto = FoodDto::fromArray($item);

            match ($foodDto->getType()) {
                FoodType::Fruit => $fruitCollection->add($foodDto),
                FoodType::Vegetable => $vegetableCollection->add($foodDto),
                default => throw new \Exception('Unknown food type'),
            };
        }

        return [$fruitCollection, $vegetableCollection];
    }

    public function saveCollection(FoodCollectionInterface $collection): void
    {
        $entities = $this->convertDtosToEntities($collection->list());

        $this->foodRepository->addMany($entities);
    }

    private function convertDtosToEntities(array $foodDtos): array
    {
        if (empty($foodDtos)) {
            return [];
        }

        $entities = [];

        foreach ($foodDtos as $foodDto) {
            $entities[] = Food::fromDto($foodDto);
        }

        return $entities;
    }

    private function transformFoodData(array $item): array
    {
        $unit = Unit::from($item['unit']);
        $quantity = (int) $item['quantity'];

        $quantityInGram = UnitConverter::convertToGram($quantity, $unit);

        $item['quantity'] = $quantityInGram;
        $item['unit'] = Unit::Gram->value;

        return $item;
    }
}
