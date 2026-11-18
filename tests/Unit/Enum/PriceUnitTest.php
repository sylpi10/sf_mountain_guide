<?php

namespace App\Tests\Unit\Enum;

use App\Enum\PriceUnit;
use PHPUnit\Framework\TestCase;

class PriceUnitTest extends TestCase
{
    public function testLabel(): void
    {
        self::assertSame('Journée', PriceUnit::Day->label());
        self::assertSame('Demi-journée', PriceUnit::HalfDay->label());
    }

    // valeurs stockées en base : les changer casserait les disciplines existantes
    public function testDatabaseValues(): void
    {
        self::assertSame(PriceUnit::Day, PriceUnit::from('day'));
        self::assertSame(PriceUnit::HalfDay, PriceUnit::from('half_day'));
    }
}
