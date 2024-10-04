<?php

namespace App\Tests\Dto;

use App\Dto\FoodDto;
use App\Enum\FoodType;
use App\Enum\Unit;
use PHPUnit\Framework\TestCase;

class FoodDtoTest extends TestCase
{
    public function testFromArray(): void
    {
        $data = [
            'id' => 1,
            'name' => 'Apple',
            'quantity' => 10.0,
            'unit' => 'kg',
            'type' => 'fruit'
        ];

        $foodDto = FoodDto::fromArray($data);

        $this->assertSame(1, $foodDto->getId());
        $this->assertSame('Apple', $foodDto->getName());
        $this->assertSame(10.0, $foodDto->getQuantity());
        $this->assertSame(Unit::Kilogram, $foodDto->getUnit());
        $this->assertSame(FoodType::Fruit, $foodDto->getType());
    }

    public function testFromArrayInvalidData(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Missing 'id' and 'unit'
        $data = [
            'name' => 'Apple',
            'quantity' => 10,
            'type' => 'fruit'
        ];

        FoodDto::fromArray($data);
    }

    public function testInvalidUnitEnum(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Invalid enum value for 'unit'
        $data = [
            'id' => 1,
            'name' => 'Apple',
            'quantity' => 10,
            'unit' => 'INVALID_UNIT',
            'type' => 'fruit'
        ];

        FoodDto::fromArray($data);
    }

    public function testInvalidFoodTypeEnum(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Invalid enum value for 'type'
        $data = [
            'id' => 1,
            'name' => 'Apple',
            'quantity' => 10,
            'unit' => 'kg',
            'type' => 'INVALID_TYPE'
        ];

        FoodDto::fromArray($data);
    }
}
