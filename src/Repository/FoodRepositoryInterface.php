<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Food;

interface FoodRepositoryInterface
{
    public function findAll(): array;
    
    public function add(Food $food): void;

    public function addMany(array $foodEntities): void;
}
