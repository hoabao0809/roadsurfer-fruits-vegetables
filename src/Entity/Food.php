<?php
declare(strict_types=1);

namespace App\Entity;

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
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(enumType: FoodType::class, nullable: true)]
    private FoodType $type;

    #[ORM\Column(type: 'float')]
    #[Assert\Positive]
    private float $quantity;

    #[ORM\Column(enumType: Unit::class, nullable: true)]
    private ?Unit $unit = null;

    #[ORM\Column(type: "datetime_immutable", nullable: false)]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct(?int $id, string $name, FoodType $type, float $quantity, ?Unit $unit = null)
    {
        if ($id !== null) {
            $this->id = $id;
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

    public function getQuantity(): float
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

    public function setQuantity(float $quantity): void
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
}

