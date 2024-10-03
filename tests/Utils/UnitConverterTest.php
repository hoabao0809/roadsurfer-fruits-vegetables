<?php

namespace App\Tests\Utils\Converter;

use App\Enum\Unit;
use App\Utils\Converter\UnitConverter;
use PHPUnit\Framework\TestCase;

class UnitConverterTest extends TestCase
{
    public function testConvertToGramFromKilogram()
    {
        $this->assertEquals(2000, UnitConverter::convertToGram(2, Unit::Kilogram));
    }

    public function testConvertToGramFromGrams()
    {
        $this->assertEquals(500, UnitConverter::convertToGram(500, Unit::Gram));
    }

    public function testConvertToGramInvalidQuantity()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid quantity');

        UnitConverter::convertToGram(0, Unit::Kilogram);
    }

    public function testConvertToGramUnsupportedUnit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported unit for conversion');

        // Create an anonymous class to represent an unsupported unit
        $unsupportedUnit = new class {};

        UnitConverter::convertToGram(1, $unsupportedUnit);
    }
}
