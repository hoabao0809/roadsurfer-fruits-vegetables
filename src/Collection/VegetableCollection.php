<?php
declare(strict_types=1);

namespace App\Collection;

use App\Dto\FoodDtoInterface;
use App\Enum\FoodType;

class VegetableCollection extends FoodCollection
{
    public function add(FoodDtoInterface $item): void
    {
        if ($item->getType() !== FoodType::Vegetable) {
            throw new \InvalidArgumentException('Only FoodDTO of type Vegetable is allowed');
        }

        parent::add($item);
    }
}