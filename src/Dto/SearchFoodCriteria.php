<?php
declare(strict_types=1);

namespace App\Dto;

class SearchFoodCriteria
{
    private ?string $name;
    private ?string $type;
    private ?int $minQuantity;
    private ?int $maxQuantity;
    private ?string $unit;

    public function __construct(
        ?string $name = null,
        ?string $type = null,
        ?int $minQuantity = null,
        ?int $maxQuantity = null,
        ?string $unit = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->minQuantity = $minQuantity;
        $this->maxQuantity = $maxQuantity;
        $this->unit = $unit;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getMinQuantity(): ?int
    {
        return $this->minQuantity;
    }

    public function getMaxQuantity(): ?int
    {
        return $this->maxQuantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }
}
