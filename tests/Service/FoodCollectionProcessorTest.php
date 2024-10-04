<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Collection\FoodCollectionInterface;
use App\Service\FoodCollectionProcessor;
use App\Service\FoodCollectionServiceInterface;
use App\Utils\FileLoader\FileLoaderInterface;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

final class FoodCollectionProcessorTest extends TestCase
{
    private FileLoaderInterface $fileLoaderMock;
    private FoodCollectionServiceInterface $foodCollectionServiceMock;
    private LoggerInterface $loggerMock;
    private FoodCollectionProcessor $foodCollectionProcessor;

    protected function setUp(): void
    {
        $this->fileLoaderMock = $this->createMock(FileLoaderInterface::class);
        $this->foodCollectionServiceMock = $this->createMock(FoodCollectionServiceInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->foodCollectionProcessor = new FoodCollectionProcessor(
            $this->fileLoaderMock,
            $this->foodCollectionServiceMock,
            $this->loggerMock
        );
    }

    public function testProcessExtractsAndSavesCollections(): void
    {
        $filePath = 'var/data/request.json';
        $foodItems = [
            ['type' => 'fruit', 'name' => 'Apple', 'quantity' => 10, 'unit' => 'kg'],
            ['type' => 'vegetable', 'name' => 'Carrot', 'quantity' => 5, 'unit' => 'kg'],
        ];

        $this->fileLoaderMock->expects($this->once())
            ->method('load')
            ->with($filePath)
            ->willReturn($foodItems);

        $fruitCollectionMock = $this->createMock(FoodCollectionInterface::class);
        $vegetableCollectionMock = $this->createMock(FoodCollectionInterface::class);

        $this->foodCollectionServiceMock->expects($this->once())
            ->method('extractCollections')
            ->with($foodItems)
            ->willReturn([$fruitCollectionMock, $vegetableCollectionMock]);

        $this->foodCollectionServiceMock->expects($this->exactly(2))
            ->method('saveCollection')
            ->withConsecutive([$fruitCollectionMock], [$vegetableCollectionMock]);

        $this->loggerMock->expects($this->exactly(2))
            ->method('info')
            ->withConsecutive(
                ['Processing food collection from file: ' . $filePath],
                ['Food collections processed successfully.']
            );

        $this->foodCollectionProcessor->process($filePath);
    }

    public function testProcessHandlesEmptyFoodItems(): void
    {
        $filePath = 'var/data/request.json';

        $this->fileLoaderMock->expects($this->once())
            ->method('load')
            ->with($filePath)
            ->willReturn([]);

        $this->loggerMock->expects($this->once())
            ->method('warning')
            ->with('No food items found in the file.');

        $this->foodCollectionProcessor->process($filePath);
    }
}
