<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Service\FoodCollectionProcessor;
use App\Utils\FileLoader\FileLoaderInterface;
use App\Service\FoodCollectionServiceInterface;
use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;

class FoodCollectionProcessorTest extends TestCase
{
    public function testProcessLoadsAndSavesCollections()
    {
        // Arrange
        $fileLoaderMock = $this->createMock(FileLoaderInterface::class);
        $foodCollectionServiceMock = $this->createMock(FoodCollectionServiceInterface::class);

        // Prepare a fake data set returned by file loader
        $foodItems = [
            ['id' => 1, 'name' => 'Apple', 'quantity' => 100, 'unit' => 'g', 'type' => 'fruit'],
            ['id' => 2, 'name' => 'Carrot', 'quantity' => 200, 'unit' => 'g', 'type' => 'vegetable']
        ];

        $fileLoaderMock->expects($this->once())
            ->method('load')
            ->with('fake_file_path')
            ->willReturn($foodItems);

        // Prepare fruit and vegetable collections
        $fruitCollection = new FruitCollection();
        $vegetableCollection = new VegetableCollection();

        // The service should extract and save collections
        $foodCollectionServiceMock->expects($this->once())
            ->method('extractCollections')
            ->with($foodItems)
            ->willReturn([$fruitCollection, $vegetableCollection]);

        $foodCollectionServiceMock->expects($this->exactly(2))
            ->method('saveCollection')
            ->withConsecutive([$fruitCollection], [$vegetableCollection]);

        $processor = new FoodCollectionProcessor($fileLoaderMock, $foodCollectionServiceMock);

        // Act
        $processor->process('fake_file_path');

        // Assert: nothing to assert here as expectations are already set on mocks
    }
}
