<?php

namespace App\Enum;

/**
 * Onglet de la page tarifs dans lequel s'affiche une section.
 */
enum PricingSeason: string
{
    case Winter = 'winter';
    case Summer = 'summer';

    public function label(): string
    {
        return match ($this) {
            self::Winter => 'Hiver',
            self::Summer => 'Été',
        };
    }

    // ancre de l'onglet (/tarifs#hiver)
    public function anchor(): string
    {
        return match ($this) {
            self::Winter => 'hiver',
            self::Summer => 'ete',
        };
    }

    /**
     * Onglet ouvert par défaut : hiver de décembre à avril, été le reste de l'année.
     */
    public static function current(?\DateTimeInterface $date = null): self
    {
        $month = (int) ($date ?? new \DateTimeImmutable())->format('n');

        return $month >= 12 || $month <= 4 ? self::Winter : self::Summer;
    }
}
