<?php
namespace App\Tests\Service;

use App\Dto\SearchFoodCriteria;
use App\Entity\Food;
use App\Enum\FoodType;
use App\Enum\Unit;
use App\Exception\ValidationException;
use App\Repository\FoodRepositoryInterface;
use App\Service\FoodService;
use App\Utils\Converter\UnitConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class FoodServiceTest extends TestCase
{
    private $foodRepositoryMock;
    private $validatorMock;
    private $foodService;

    protected function setUp(): void
    {
        $this->foodRepositoryMock = $this->createMock(FoodRepositoryInterface::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->foodService = new FoodService($this->foodRepositoryMock, $this->validatorMock);
    }
    public function testGetAllFoodsWithoutUnitConversion(): void
    {
        $foods = [
            new Food(1, 'Apple', FoodType::Fruit, 500, Unit::Gram),
            new Food(2, 'Banana', FoodType::Fruit, 1, Unit::Kilogram),
        ];

        $this->foodRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($foods);

        $result = $this->foodService->getAllFoods(null);

        $expectedResponse = [
            [
                'id' => 1,
                'name' => 'Apple',
                'quantity' => 500.0,
                'unit' => Unit::Gram->value,
                'type' => FoodType::Fruit->value,
                'createdAt' => $foods[0]->getCreatedAt()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'Banana',
                'quantity' => 1.0,
                'unit' => Unit::Kilogram->value,
                'type' => FoodType::Fruit->value,
                'createdAt' => $foods[1]->getCreatedAt()->format('Y-m-d H:i:s'),
            ],
        ];

        $this->assertEquals($expectedResponse, $result);
    }

    public function testGetAllFoodsWithKilogramConversion(): void
    {
        $foods = [
            new Food(1, 'Apple', FoodType::Fruit, 500, Unit::Gram),
        ];

        $this->foodRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($foods);

        $result = $this->foodService->getAllFoods(Unit::Kilogram->value);

        $expectedResponse = [
            [
                'id' => 1,
                'name' => 'Apple',
                'quantity' => UnitConverter::convertToKilogram(500),
                'unit' => Unit::Kilogram->value,
                'type' => FoodType::Fruit->value,
                'createdAt' => $foods[0]->getCreatedAt()->format('Y-m-d H:i:s'),
            ],
        ];

        $this->assertEquals($expectedResponse, $result);
    }

    public function testGetAllFoodsWithGramConversion(): void
    {
        $foods = [
            new Food(2, 'Banana', FoodType::Fruit, 1, Unit::Kilogram),
        ];

        $this->foodRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($foods);

        $result = $this->foodService->getAllFoods(Unit::Gram->value);

        $expectedResponse = [
            [
                'id' => 2,
                'name' => 'Banana',
                'quantity' => UnitConverter::convertToGram(1), // 1kg -> 1000g
                'unit' => Unit::Gram->value,
                'type' => FoodType::Fruit->value,
                'createdAt' => $foods[0]->getCreatedAt()->format('Y-m-d H:i:s'),
            ],
        ];

        $this->assertEquals($expectedResponse, $result);
    }

    public function testCreateFoodSuccess(): void
    {
        $data = [
            'name' => 'Banana',
            'type' => FoodType::Fruit->value,
            'quantity' => 500,
            'unit' => Unit::Gram->value
        ];

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList());

        $this->foodRepositoryMock->expects($this->once())->method('add');

        $result = $this->foodService->createFood($data);

        $this->assertInstanceOf(Food::class, $result);
    }

    public function testCreateFoodWithValidationErrors(): void
    {
        $data = [
            'name' => 'Banana',
            'type' => FoodType::Fruit->value,
            'quantity' => 500,
            'unit' => Unit::Gram->value
        ];

        $violationMock = $this->createMock(ConstraintViolation::class);
        $violationMock->method('getMessage')->willReturn('Invalid name');

        $violationList = new ConstraintViolationList([$violationMock]);

        $this->validatorMock->method('validate')->willReturn($violationList);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation failed');

        $this->foodService->createFood($data);
    }

    public function testSearchFoodsWithEmptyConditions(): void
    {
        $conditions = [];

        $this->foodRepositoryMock->expects($this->once())
            ->method('search')
            ->with($this->isInstanceOf(SearchFoodCriteria::class))
            ->willReturn([]);

        $result = $this->foodService->searchFoods($conditions);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testSearchFoodsWithSpecificConditions(): void
    {
        $conditions = [
            'name' => 'Apple',
            'type' => FoodType::Fruit->value,
            'min_quantity' => 100,
            'max_quantity' => 500,
            'unit' => Unit::Gram->value,
        ];

        $expectedResult = [
            new Food(1, 'Apple', FoodType::Fruit, 200, Unit::Gram),
        ];

        $this->foodRepositoryMock->expects($this->once())
            ->method('search')
            ->with($this->callback(function (SearchFoodCriteria $criteria) use ($conditions) {
                return $criteria->getName() === $conditions['name']
                    && $criteria->getType() === $conditions['type']
                    && $criteria->getMinQuantity() === (int)$conditions['min_quantity']
                    && $criteria->getMaxQuantity() === (int)$conditions['max_quantity']
                    && $criteria->getUnit() === $conditions['unit'];
            }))
            ->willReturn($expectedResult);

        $result = $this->foodService->searchFoods($conditions);

        $this->assertEquals($expectedResult, $result);
    }
}
