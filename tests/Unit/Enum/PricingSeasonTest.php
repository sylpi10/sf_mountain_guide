<?php

namespace App\Tests\Unit\Enum;

use App\Enum\PricingSeason;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PricingSeasonTest extends TestCase
{
    public static function monthProvider(): iterable
    {
        // hiver d'octobre à avril (cas par défaut : 10 et 5)
        yield 'janvier' => [10, 5, 1, PricingSeason::Winter];
        yield 'avril' => [10, 5, 4, PricingSeason::Winter];
        yield 'mai' => [10, 5, 5, PricingSeason::Summer];
        yield 'septembre' => [10, 5, 9, PricingSeason::Summer];
        yield 'octobre' => [10, 5, 10, PricingSeason::Winter];
        yield 'décembre' => [10, 5, 12, PricingSeason::Winter];
        // bornes inversées : « hiver » de mars à juin
        yield 'inversé, février' => [3, 7, 2, PricingSeason::Summer];
        yield 'inversé, mars' => [3, 7, 3, PricingSeason::Winter];
        yield 'inversé, juillet' => [3, 7, 7, PricingSeason::Summer];
    }

    #[DataProvider('monthProvider')]
    public function testCurrent(int $winterFrom, int $summerFrom, int $month, PricingSeason $expected): void
    {
        $date = new \DateTimeImmutable(sprintf('2026-%02d-15', $month));

        self::assertSame($expected, PricingSeason::current($winterFrom, $summerFrom, $date));
    }

    public function testLabelAndAnchor(): void
    {
        self::assertSame('Hiver', PricingSeason::Winter->label());
        self::assertSame('Été', PricingSeason::Summer->label());

        // ancres des onglets : /tarifs#hiver, /tarifs#ete
        self::assertSame('hiver', PricingSeason::Winter->anchor());
        self::assertSame('ete', PricingSeason::Summer->anchor());
    }
}
