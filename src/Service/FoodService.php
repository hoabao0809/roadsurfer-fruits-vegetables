<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\Response\FoodResponseDto;
use App\Dto\SearchFoodCriteria;
use App\Entity\Food;
use App\Enum\FoodType;
use App\Enum\Unit;
use App\Exception\ValidationException;
use App\Repository\FoodRepositoryInterface;
use App\Utils\Converter\UnitConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FoodService implements FoodServiceInterface
{
    private FoodRepositoryInterface $foodRepository;
    private ValidatorInterface $validator;

    public function __construct(FoodRepositoryInterface $foodRepository, ValidatorInterface $validator)
    {
        $this->foodRepository = $foodRepository;
        $this->validator = $validator;
    }

    public function getAllFoods(?string $targetUnit): array
    {
        $foods = $this->foodRepository->findAll();

        $response = [];
        foreach ($foods as $food) {
            $unit = $food->getUnit()->value;
            $quantity = $food->getQuantity();

            if ($targetUnit) {
                $unit = $targetUnit;
                $quantity = $targetUnit === Unit::Kilogram->value ? UnitConverter::convertToKilogram($quantity) : UnitConverter::convertToGram($quantity);
            }
          
            $response[] = [
                'id' => $food->getId(),
                'name' => $food->getName(),
                'quantity' => $quantity,
                'unit' => $unit,
                'type' => $food->getType()->value,
                'createdAt' => $food->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }
     
        return $response;
    }

    public function createFood(array $data): Food
    {
        try {
            $foodType = FoodType::from($data['type']);
            $unit = isset($data['unit']) ? Unit::from($data['unit']) : null;
        } catch (\ValueError $e) {
            throw new ValidationException($e->getMessage());
        }

        $food = new Food(
            null,
            $data['name'],
            $foodType,
            $data['quantity'],
            $unit
        );

        $errors = $this->validator->validate($food);
        if (count($errors) > 0) {
            throw ValidationException::fromConstraintViolations($errors);
        }

        $this->foodRepository->add($food);

        return $food;
    }

    public function searchFoods(array $conditions): array
    {
        $criteria = new SearchFoodCriteria(
            $conditions['name'] ?? null,
            $conditions['type'] ?? null,
            isset($conditions['min_quantity']) ? (int)$conditions['min_quantity'] : null,
            isset($conditions['max_quantity']) ? (int)$conditions['max_quantity'] : null,
            $conditions['unit'] ?? null
        );

        return $this->foodRepository->search($criteria);
    }
}
