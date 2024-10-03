<?php
declare(strict_types=1);

namespace App\Dto\Response;

use App\Enum\FoodType;
use App\Enum\Unit;
use JMS\Serializer\Annotation as Serialization;

class FoodResponseDto
{
    /**
     * @Serialization\Type("int")
     */
    public int $id;

    /**
     * @Serialization\Type("string")
     */
    public string $name;

    /**
     * @Serialization\Type("enum<App\Enum\FoodType>")
     */
    public FoodType $type;

    /**
     * @Serialization\Type("int")
     */
    public int $quantity;

    /**
     * @Serialization\Type("enum<App\Enum\Unit>")
     */
    public Unit $unit;

    /**
     * @Serialization\Type("DateTime<'Y-m-d\TH:i:s'>")
     */
    public \DateTime $createdAt;

    /**
     * FoodResponseDto constructor.
     * 
     * @param int $id
     * @param string $name
     * @param int $quantity
     * @param Unit $unit
     * @param FoodType $type
     * @param \DateTime $createdAt
     */
    public function __construct(
        int $id,
        string $name,
        int $quantity,
        Unit $unit,
        FoodType $type,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->type = $type;
        $this->createdAt = $createdAt;
    }
}
