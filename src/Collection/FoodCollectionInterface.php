<?php
declare(strict_types=1);

namespace App\Collection;

use App\Dto\FoodDtoInterface;

interface FoodCollectionInterface
{
    public function add(FoodDtoInterface $item): void;

    public function remove(int $id): void;

    public function list(): array;
}