<?php
declare(strict_types=1);

namespace App\Entity;

use App\Dto\FoodDtoInterface;
use App\Enum\FoodType;
use App\Enum\Unit;
use App\Repository\FoodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(enumType: FoodType::class)]
    #[Assert\Choice(choices: ['fruit', 'vegetable'], message: 'Invalid food type.')]
    private FoodType $type;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive]
    private int $quantity;

    #[ORM\Column(enumType: Unit::class, nullable: true)]
    #[Assert\Choice(choices: [Unit::Gram, Unit::Kilogram])]
    private ?Unit $unit = null;

    #[ORM\Column(type: "datetime_immutable", nullable: false)]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    // Constructor
    public function __construct(?int $id, string $name, FoodType $type, int $quantity, ?Unit $unit = null)
    {
        if ($id !== null) {
            $this->id = $id; // Set the ID if provided
        }
        $this->name = $name;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->created_at = new \DateTimeImmutable();
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): FoodType
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setType(FoodType $type): void
    {
        $this->type = $type;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setUnit(Unit $unit): void
    {
        $this->unit = $unit;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }

    public function updateQuantity(int $newQuantity): void
    {
        $this->quantity = $newQuantity;
        $this->updated_at = new \DateTimeImmutable();
    }
    
    public static function fromDto(FoodDtoInterface $foodDto): self
    {
        return new self(
            $foodDto->getId(), // Set ID from the DTO
            $foodDto->getName(),
            $foodDto->getType(),
            $foodDto->getQuantity(),
            $foodDto->getUnit()
        );
    }
}

