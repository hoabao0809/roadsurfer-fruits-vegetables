<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Collection\FruitCollection;
use App\Dto\FoodDto;
use App\Enum\Unit;
use App\Enum\FoodType;

class FruitCollectionTest extends TestCase
{
    public function testAddItems()
    {
        // Arrange
        $collection = new FruitCollection();
        $foodDto1 = new FoodDto(1, 'Apple', 100, Unit::Gram, FoodType::Fruit);
        $foodDto2 = new FoodDto(2, 'Banana', 150, Unit::Gram, FoodType::Fruit);

        // Act
        $collection->add($foodDto1);
        $collection->add($foodDto2);
        $items = $collection->list();

        // Assert
        $this->assertCount(2, $items);
    }

    public function testListItems()
    {
        // Arrange
        $collection = new FruitCollection();
        $foodDto1 = new FoodDto(1, 'Apple', 100, Unit::Gram, FoodType::Fruit);
        $foodDto2 = new FoodDto(2, 'Banana', 150, Unit::Gram, FoodType::Fruit);

        $collection->add($foodDto1);
        $collection->add($foodDto2);

        // Act
        $items = $collection->list();

        // Assert
        $this->assertArrayHasKey(1, $items);
        $this->assertArrayHasKey(2, $items);
    }

    public function testRemoveItem()
    {
        // Arrange
        $collection = new FruitCollection();
        $foodDto = new FoodDto(1, 'Apple', 100, Unit::Gram, FoodType::Fruit);

        $collection->add($foodDto);

        // Act
        $collection->remove(1);
        $items = $collection->list();

        // Assert
        $this->assertCount(0, $items);
    }

    public function testAddNonVegetableThrowsException()
    {
        // Arrange
        $collection = new FruitCollection();
        $foodDto = new FoodDto(1, 'Apple', 100, Unit::Gram, FoodType::Vegetable);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only FoodDTO of type Fruit is allowed');

        // Act
        $collection->add($foodDto);
    }
}
