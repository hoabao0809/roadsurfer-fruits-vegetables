<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Dto\FoodDto;
use App\Entity\Food;
use App\Service\FoodCollectionService;
use App\Repository\FoodRepositoryInterface;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

final class FoodCollectionServiceTest extends TestCase
{
    private FoodRepositoryInterface $foodRepositoryMock;
    private LoggerInterface $loggerMock;

    protected function setUp(): void
    {
        $this->foodRepositoryMock = $this->createMock(FoodRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
    }

    public function testExtractCollections(): void
    {
        $foodCollectionService = new FoodCollectionService($this->foodRepositoryMock, $this->loggerMock);

        $foodItems = [
            ['id' => 1, 'name' => 'Apple', 'quantity' => 2, 'unit' => 'kg', 'type' => 'fruit'],
            ['id' => 2, 'name' => 'Carrot', 'quantity' => 1, 'unit' => 'kg', 'type' => 'vegetable'],
            ['id' => 3, 'name' => 'Invalid Item', 'quantity' => 'invalid', 'unit' => 'kg', 'type' => 'fruit'], // Invalid
        ];

        [$fruitCollection, $vegetableCollection] = $foodCollectionService->extractCollections($foodItems);

        $this->assertInstanceOf(FruitCollection::class, $fruitCollection);
        $this->assertInstanceOf(VegetableCollection::class, $vegetableCollection);
        $this->assertCount(1, $fruitCollection->list()); // Only valid fruit item
        $this->assertCount(1, $vegetableCollection->list()); // One valid vegetable
    }

    public function testSaveCollection(): void
    {
        $foodCollectionService = new FoodCollectionService($this->foodRepositoryMock, $this->loggerMock);

        $fruitCollection = new FruitCollection();
        $foodDto = FoodDto::fromArray([
            'id' => 1,
            'name' => 'Apple',
            'quantity' => 2000,
            'unit' => 'g',
            'type' => 'fruit'
        ]);

        $fruitCollection->add($foodDto);

        $this->foodRepositoryMock
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($entity) {
                $this->assertInstanceOf(Food::class, $entity);
                return true;
            }));

        $foodCollectionService->saveCollection($fruitCollection);
    }
}
