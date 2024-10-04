<?php

namespace App\Tests\Controller\Api;

use App\Controller\Api\FoodImportController;
use App\Service\FoodCollectionProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FoodImportControllerTest extends TestCase
{
    private $foodCollectionProcessorMock;
    private $filePath = '/path/to/food.json';

    protected function setUp(): void
    {
        // Create a mock for FoodCollectionProcessor
        $this->foodCollectionProcessorMock = $this->createMock(FoodCollectionProcessor::class);
    }

    public function testImportFoodSuccess(): void
    {
        // Expect process method to be called once
        $this->foodCollectionProcessorMock->expects($this->once())
            ->method('process')
            ->with($this->filePath);

        // Create a partial mock of the FoodImportController to mock the json() method
        $controller = $this->getMockBuilder(FoodImportController::class)
            ->setConstructorArgs([$this->foodCollectionProcessorMock, $this->filePath])
            ->onlyMethods(['json'])
            ->getMock();

        // Mock the json() method to return a JsonResponse
        $controller->expects($this->once())
            ->method('json')
            ->with([
                'status' => 'success',
                'message' => 'Food collections processed and stored.',
            ], Response::HTTP_OK)
            ->willReturn(new JsonResponse([
                'status' => 'success',
                'message' => 'Food collections processed and stored.',
            ], Response::HTTP_OK));

        // Call the importFood method
        $response = $controller->importFood();

        // Assert that the response is successful (HTTP 200)
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Assert that the response has the success message
        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'success',
            'message' => 'Food collections processed and stored.',
        ]), $response->getContent());
    }

    public function testImportFoodFileNotFound(): void
    {
        // Simulate FileNotFoundException
        $this->foodCollectionProcessorMock->expects($this->once())
            ->method('process')
            ->with($this->filePath)
            ->willThrowException(new FileNotFoundException('File not found.'));

        // Create a partial mock of the FoodImportController to mock the json() method
        $controller = $this->getMockBuilder(FoodImportController::class)
            ->setConstructorArgs([$this->foodCollectionProcessorMock, $this->filePath])
            ->onlyMethods(['json'])
            ->getMock();

        // Mock the json() method to return a JsonResponse
        $controller->expects($this->once())
            ->method('json')
            ->with([
                'status' => 'error',
                'message' => 'File not found.',
            ], Response::HTTP_NOT_FOUND)
            ->willReturn(new JsonResponse([
                'status' => 'error',
                'message' => 'File not found.',
            ], Response::HTTP_NOT_FOUND));

        // Call the importFood method
        $response = $controller->importFood();

        // Assert that the response is a 404 (file not found)
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        // Assert that the response has the correct error message
        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'error',
            'message' => 'File not found.',
        ]), $response->getContent());
    }

    public function testImportFoodUnexpectedError(): void
    {
        // Simulate a general exception
        $this->foodCollectionProcessorMock->expects($this->once())
            ->method('process')
            ->with($this->filePath)
            ->willThrowException(new \Exception());

        // Create a partial mock of the FoodImportController to mock the json() method
        $controller = $this->getMockBuilder(FoodImportController::class)
            ->setConstructorArgs([$this->foodCollectionProcessorMock, $this->filePath])
            ->onlyMethods(['json'])
            ->getMock();

        // Mock the json() method to return a JsonResponse
        $controller->expects($this->once())
            ->method('json')
            ->with([
                'status' => 'error',
                'message' => 'An unexpected error occurred while importing the food.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
            ->willReturn(new JsonResponse([
                'status' => 'error',
                'message' => 'An unexpected error occurred while importing the food.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR));

        // Call the importFood method
        $response = $controller->importFood();

        // Assert that the response is a 500 (internal server error)
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // Assert that the response has the correct error message
        $this->assertJsonStringEqualsJsonString(json_encode([
            'status' => 'error',
            'message' => 'An unexpected error occurred while importing the food.',
        ]), $response->getContent());
    }
}
