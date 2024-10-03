<?php
declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Food;
use App\Enum\Unit;
use App\Utils\Converter\UnitConverter;

class FoodDtoConverter
{
    /**
     * Convert a Food entity to a FoodResponseDto.
     *
     * @param Food $food
     * @param Unit $unit
     * @return FoodResponseDto
     */
    public function fromEntity(Food $food, Unit $unit = Unit::Kilogram): FoodResponseDto
    {
        $quantity = $food->getQuantity();

        if ($unit === Unit::Kilogram) {
            $convertedQty = UnitConverter::convertToGram($quantity);
            $quantity = $convertedQty;
        }

        // Create FoodResponseDto using the constructor
        $dto = new FoodResponseDto(
            $food->getId(),
            $food->getName(),
            $quantity,
            $unit ?? Unit::Gram,
            $food->getType(),
            $food->getCreatedAt(),
        );

        return $dto;
    }

    /**
     * Convert an array of Food entities to an array of FoodResponseDto.
     *
     * @param Food[] $foods
     * @param Unit|null $unit
     * @return FoodResponseDto[]
     */
    public function fromEntities(array $foods, ?Unit $unit = null): array
    {
        if (empty($foods)) {
            return [];
        }

        $dtos = [];

        foreach ($foods as $food) {
            $dtos[] = $this->fromEntity($food, $unit);
        }

        return $dtos;
    }
}
