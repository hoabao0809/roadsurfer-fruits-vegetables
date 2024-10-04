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
use Psr\Log\LoggerInterface;

class FoodCollectionService implements FoodCollectionServiceInterface
{
    private FoodRepositoryInterface $foodRepository;

    private LoggerInterface $logger;

    public function __construct(FoodRepositoryInterface $foodRepository, LoggerInterface $logger)
    {
        $this->foodRepository = $foodRepository;
        $this->logger = $logger;
    }

    public function extractCollections(array $foodItems): array
    {
        $fruitCollection = new FruitCollection();
        $vegetableCollection = new VegetableCollection();

        foreach ($foodItems as $item) {
            // Validate food item before transforming
            if (!$this->validateFoodItem($item)) {
                $this->logger->error('Invalid food item detected', [
                    'item' => $item,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                continue;
            }

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

        if (empty($entities)) {
            return;
        }

        foreach ($entities as $entity) {
            $this->foodRepository->add($entity);
        }
    }

    private function convertDtosToEntities(array $foodDtos): array
    {
        if (empty($foodDtos)) {
            return [];
        }

        $entities = [];

        /** @var \App\Dto\FoodDtoInterface $foodDto */
        foreach ($foodDtos as $foodDto) {
            $entities[] = new Food(
                $foodDto->getId(),
                $foodDto->getName(),
                $foodDto->getType(),
                $foodDto->getQuantity(),
                $foodDto->getUnit()
            );
        }

        return $entities;
    }

    private function transformFoodData(array $item): array
    {
        $unit = Unit::from($item['unit']);
        $quantity = $item['quantity'];

        $quantityInGram = UnitConverter::convertToGram($quantity, $unit);

        $item['quantity'] = $quantityInGram;
        $item['unit'] = Unit::Gram->value;

        return $item;
    }

    private function validateFoodItem(array $item): bool
    {
        $requiredFields = [
            'id' => 'integer',
            'name' => 'string',
            'quantity' => 'integer',
            'unit' => 'string', 
            'type' => 'string'
        ];

        foreach ($requiredFields as $field => $type) {
            if (!array_key_exists($field, $item)) {
                // Log missing field error
                $this->logger->error('Missing field in food item', [
                    'item' => $item,
                    'missing_field' => $field,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                return false;
            }

            // Validate field type
            if (gettype($item[$field]) !== $type) {
                $this->logger->error('Type mismatch in food item', [
                    'item' => $item,
                    'expected_type' => $type,
                    'actual_type' => gettype($item[$field]),
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                return false;
            }
        }

        return true;
    }
}
