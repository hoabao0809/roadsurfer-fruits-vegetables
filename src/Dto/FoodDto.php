<?php
declare(strict_types=1);

namespace App\Dto;

use App\Enum\FoodType;
use App\Enum\Unit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO class for Food items.
 */
final class FoodDto implements FoodDtoInterface
{
    /**
     * @var ?int Food ID
     * 
     * @Assert\NotBlank
     * @Assert\Type("integer")
     */
    private ?int $id;

    /**
     * @var string Name of the food item
     * 
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private string $name;

    /**
     * @var int Quantity of the food item
     * 
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Positive
     */
    private int $quantity;

    /**
     * @var Unit Unit type of the food item
     * 
     * @Assert\NotBlank
     */
    private Unit $unit;

    /**
     * @var FoodType Type of the food item
     * 
     * @Assert\NotBlank
     */
    private FoodType $type;

    /**
     * FoodDto constructor.
     *
     * @param ?int $id
     * @param string $name
     * @param int $quantity
     * @param Unit $unit
     * @param FoodType $type
     */
    public function __construct(
        ?int $id,
        string $name,
        int $quantity,
        Unit $unit,
        FoodType $type
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->type = $type;
    }

    /**
     * Get the ID of the food item.
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name of the food item.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the quantity of the food item.
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Get the unit type of the food item.
     *
     * @return Unit
     */
    public function getUnit(): Unit
    {
        return $this->unit;
    }

    /**
     * Must be implemented in child classes.
     *
     * @return FoodType
     */
    public function getType(): FoodType 
    {
        return $this->type;
    }

    /**
     * Create FoodDto from array data.
     *
     * @param array $data
     * @return self
     * @throws \InvalidArgumentException if required fields are missing or invalid.
     */
    public static function fromArray(array $data): self
    {
        try {
            $unit = Unit::from($data['unit']);
            $type = FoodType::from($data['type']);
    
            return new self(
                $data['id'] ?? null,
                (string) $data['name'],
                (int) $data['quantity'],
                $unit,
                $type
            );
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Error creating FoodDto: ' . $e->getMessage());
        }
    }
}
