<?php

namespace App\Service;

use App\Dto\CreateFoodDto;
use App\Entity\Food;

interface FoodServiceInterface
{
    public function getAllFoods(?string $targetUnit): array;

    public function createFood(array $data): Food;

    public function searchFoods(array $conditions): array;
}
