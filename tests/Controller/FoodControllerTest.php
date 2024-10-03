<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Controller\Api\FoodController;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class FoodControllerTest extends KernelTestCase
{
    private FoodRepository $foodRepository;
    private FoodController $foodController;

    protected function setUp(): void
    {
        self::bootKernel(); // Boot the kernel to get access to the service container

        $this->foodRepository = $this->createMock(FoodRepository::class);
        // Get the validator from the container
        $validator = self::$container->get('validator');
        $this->foodController = new FoodController($this->foodRepository, $validator);
    }

    public function testIndexReturnsJsonResponse(): void
    {
        // Arrange
        $expectedFoods = [
            ['id' => 1, 'name' => 'Apple'],
            ['id' => 2, 'name' => 'Banana'],
        ];
        
        // Mock the repository to return the expected foods
        $this->foodRepository
            ->method('findAll')
            ->willReturn($expectedFoods);
        
        // Act
        $response = $this->foodController->index();

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame($expectedFoods, json_decode($response->getContent(), true));
        $this->assertSame(200, $response->getStatusCode());
    }
}
