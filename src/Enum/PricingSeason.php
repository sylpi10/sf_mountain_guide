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
     * Onglet ouvert par défaut selon le mois : hiver à partir de $winterFrom, été à partir de $summerFrom
     * (ex. 10 et 5 : hiver d'octobre à avril, été de mai à septembre).
     */
    public static function current(int $winterFrom = 10, int $summerFrom = 5, ?\DateTimeInterface $date = null): self
    {
        $month = (int) ($date ?? new \DateTimeImmutable())->format('n');
        $winter = $winterFrom > $summerFrom
            ? $month >= $winterFrom || $month < $summerFrom
            : $month >= $winterFrom && $month < $summerFrom;

        return $winter ? self::Winter : self::Summer;
    }
}
