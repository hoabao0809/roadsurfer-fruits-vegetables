<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Food;
use App\Enum\FoodType;
use App\Enum\Unit;
use PHPUnit\Framework\TestCase;

class FoodTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $food = new Food(null, "Apple", FoodType::Fruit, 1.5, Unit::Kilogram);
        
        $this->assertEquals("Apple", $food->getName());
        $this->assertEquals(FoodType::Fruit, $food->getType());
        $this->assertEquals(1.5, $food->getQuantity());
        $this->assertEquals(Unit::Kilogram, $food->getUnit());
        $this->assertInstanceOf(\DateTimeImmutable::class, $food->getCreatedAt());
        $this->assertNull($food->getUpdatedAt()); // Since it's not set in the constructor
    }

    public function testSettersAndGetters(): void
    {
        $food = new Food(null, "Banana", FoodType::Fruit, 2.0);

        $food->setName("Banana");
        $food->setType(FoodType::Fruit);
        $food->setQuantity(2.0);
        $food->setUnit(Unit::Kilogram);

        $this->assertEquals("Banana", $food->getName());
        $this->assertEquals(FoodType::Fruit, $food->getType());
        $this->assertEquals(2.0, $food->getQuantity());
        $this->assertEquals(Unit::Kilogram, $food->getUnit());
    }

    public function testNullableUnit(): void
    {
        $food = new Food(null, "Water", FoodType::Vegetable, 1.0, null);
        
        $this->assertNull($food->getUnit());
    }
}
