<?php

namespace App\Enum;

/**
 * Unité du prix « à partir de » affiché sur la carte d'une discipline.
 */
enum PriceUnit: string
{
    case Day = 'day';
    case HalfDay = 'half_day';

    public function label(): string
    {
        return match ($this) {
            self::Day => 'Journée',
            self::HalfDay => 'Demi-journée',
        };
    }
}
