<?php
declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Entity\Food;
use App\Enum\FoodType;
use App\Enum\Unit;
use App\Service\FoodServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FoodControllerTest extends WebTestCase
{
    private FoodServiceInterface $foodService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock for FoodServiceInterface
        $this->foodService = $this->createMock(FoodServiceInterface::class);
    }

    public function testIndex(): void
    {
        // Set up the FoodService mock
        $this->foodService->method('getAllFoods')->willReturn([
            ['id' => 1, 'name' => 'Apple', 'quantity' => 500, 'unit' => 'g', 'type' => 'fruit', 'createdAt' => '2024-10-04 05:17:03'],
        ]);

        // Use the Symfony client to make a request
        $client = static::createClient();
        $client->getContainer()->set(FoodServiceInterface::class, $this->foodService);

        // Send a request to the index route
        $client->request('GET', '/api/foods?unit=g');

        // Get the response
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'status' => 'success',
                'message' => 'Foods retrieved successfully.',
                'data' => [
                    ['id' => 1, 'name' => 'Apple', 'quantity' => 500, 'unit' => 'g', 'type' => 'fruit', 'createdAt' => '2024-10-04 05:17:03'],
                ],
            ]),
            $response->getContent()
        );
    }

    public function testAdd(): void
    {
        $data = [
            'name' => 'Banana',
            'quantity' => 1,
            'unit' => 'kg',
            'type' => 'fruit',
        ];

        $food = new Food(null, 'Banana', FoodType::from('fruit'), 1.0, Unit::from('kg'));

        $this->foodService->method('createFood')->willReturn($food);

        $client = static::createClient();
        $client->getContainer()->set(FoodServiceInterface::class, $this->foodService);

        $client->request('POST', '/api/foods', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testSearch(): void
    {
        $conditions = [
            'name' => 'Apple',
            'type' => 'fruit',
        ];

        $this->foodService->method('searchFoods')->willReturn([
            ['id' => 1, 'name' => 'Apple', 'quantity' => 500, 'unit' => 'g', 'type' => 'fruit', 'createdAt' => '2024-10-04 05:17:03'],
        ]);

        $client = static::createClient();
        $client->getContainer()->set(FoodServiceInterface::class, $this->foodService);

        $client->request('GET', '/api/foods/search?' . http_build_query($conditions));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'status' => 'success',
                'message' => 'Foods retrieved successfully.',
                'data' => [
                    ['id' => 1, 'name' => 'Apple', 'quantity' => 500, 'unit' => 'g', 'type' => 'fruit', 'createdAt' => '2024-10-04 05:17:03'],
                ],
            ]),
            $response->getContent()
        );
    }
}
