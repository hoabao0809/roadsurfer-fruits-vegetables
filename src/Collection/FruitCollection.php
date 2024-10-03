<?php
declare(strict_types=1);

namespace App\Collection;

use App\Dto\FoodDtoInterface;
use App\Enum\FoodType;
class FruitCollection extends FoodCollection
{
    public function add(FoodDtoInterface $item): void
    {
        if ($item->getType() !== FoodType::Fruit) {
            throw new \InvalidArgumentException('Only FoodDTO of type Fruit is allowed');
        }
        
        parent::add($item);
    }
}