<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Collection\VegetableCollection;
use App\Dto\FoodDto;
use App\Enum\Unit;
use App\Enum\FoodType;

class VegetableCollectionTest extends TestCase
{
    public function testAddItems()
    {
        // Arrange
        $collection = new VegetableCollection();
        $foodDto1 = new FoodDto(1, 'Carrot', 100, Unit::Gram, FoodType::Vegetable);
        $foodDto2 = new FoodDto(2, 'Broccoli', 150, Unit::Gram, FoodType::Vegetable);

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
        $collection = new VegetableCollection();
        $foodDto1 = new FoodDto(1, 'Carrot', 100, Unit::Gram, FoodType::Vegetable);
        $foodDto2 = new FoodDto(2, 'Broccoli', 150, Unit::Gram, FoodType::Vegetable);

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
        $collection = new VegetableCollection();
        $foodDto = new FoodDto(1, 'Carrot', 100, Unit::Gram, FoodType::Vegetable);

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
        $collection = new VegetableCollection();
        $foodDto = new FoodDto(1, 'Apple', 100, Unit::Gram, FoodType::Fruit);

        // Expect an InvalidArgumentException
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only FoodDTO of type Vegetable is allowed');

        // Act
        $collection->add($foodDto);
    }
}
