<?php
declare(strict_types=1);

namespace App\Utils\Converter;

use App\Enum\Unit;

class UnitConverter
{
    public static function convertToGram(float $quantity, $currentUnit = Unit::Kilogram): float
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Invalid quantity');
        }
    
        if (!($currentUnit instanceof Unit)) {
            throw new \InvalidArgumentException('Unsupported unit for conversion');
        }
    
        return match ($currentUnit) {
            Unit::Kilogram => $quantity * 1000,
            Unit::Gram => $quantity,
            default => throw new \InvalidArgumentException('Unsupported unit for conversion'),
        };
    }

    public static function convertToKilogram(float $quantity, $currentUnit = Unit::Gram): float
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Invalid quantity');
        }

        if (!($currentUnit instanceof Unit)) {
            throw new \InvalidArgumentException('Unsupported unit for conversion');
        }
    
        return match ($currentUnit) {
            Unit::Gram => $quantity / 1000,
            Unit::Kilogram => $quantity,
            default => throw new \InvalidArgumentException('Unsupported unit for conversion'),
        };
    }
}
