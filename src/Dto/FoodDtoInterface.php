<?php
declare(strict_types=1);

namespace App\Dto;

use App\Enum\FoodType;
use App\Enum\Unit;

interface FoodDtoInterface
{
    public function getId(): ?int;
    public function getName(): string;
    public function getType(): FoodType;
    public function getQuantity(): float;
    public function getUnit(): Unit;

}