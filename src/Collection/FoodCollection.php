<?php
declare(strict_types=1);

namespace App\Collection;

use App\Dto\FoodDtoInterface;

abstract class FoodCollection implements FoodCollectionInterface
{
    protected array $foods = [];

    public function add(FoodDtoInterface $item): void
    {
        $this->foods[$item->getId()] = $item;
    }

    public function remove(int $id): void
    {
        unset($this->foods[$id]);
    }

    public function list(): array
    {
        return $this->foods;
    }

    public function search(string $filter): array
    {
        return []; // Implement search logic based on filter
    }
}
