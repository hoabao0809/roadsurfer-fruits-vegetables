<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Service\FoodCollectionService;
use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Repository\FoodRepositoryInterface;
use App\Dto\FoodDto;
use App\Enum\Unit;
use App\Enum\FoodType;

class FoodCollectionServiceTest extends TestCase
{
    public function testExtractCollections()
    {
        // Arrange
        $repositoryMock = $this->createMock(FoodRepositoryInterface::class);
        $service = new FoodCollectionService($repositoryMock);

        $foodItems = [
            ['id' => 1, 'name' => 'Apple', 'quantity' => 100, 'unit' => 'g', 'type' => 'fruit'],
            ['id' => 2, 'name' => 'Carrot', 'quantity' => 200, 'unit' => 'g', 'type' => 'vegetable']
        ];

        // Act
        [$fruitCollection, $vegetableCollection] = $service->extractCollections($foodItems);

        // Assert
        $this->assertInstanceOf(FruitCollection::class, $fruitCollection);
        $this->assertInstanceOf(VegetableCollection::class, $vegetableCollection);
        $this->assertCount(1, $fruitCollection->list());
        $this->assertCount(1, $vegetableCollection->list());
    }

    public function testSaveCollection()
    {
        // Arrange
        $repositoryMock = $this->createMock(FoodRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('addMany')
            ->with($this->isType('array'));

        $service = new FoodCollectionService($repositoryMock);
        $collection = new FruitCollection();

        $foodDto = new FoodDto(1, 'Apple', 100, Unit::Gram, FoodType::Fruit);
        $collection->add($foodDto);

        // Act
        $service->saveCollection($collection);

        // Assert: The mock ensures that addMany is called with the correct array of entities
    }
}
