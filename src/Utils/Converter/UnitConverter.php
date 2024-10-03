<?php
declare(strict_types=1);

namespace App\Utils\Converter;

use App\Enum\Unit;

class UnitConverter
{
    public static function convertToGram(int $quantity, $unit = Unit::Kilogram): int
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Invalid quantity');
        }
    
        // Check if the provided unit is an instance of the Unit enum
        if (!($unit instanceof Unit)) {
            throw new \InvalidArgumentException('Unsupported unit for conversion');
        }
    
        return match ($unit) {
            Unit::Kilogram => $quantity * 1000,
            Unit::Gram => $quantity,
            default => throw new \InvalidArgumentException('Unsupported unit for conversion'),
        };
    }
}
