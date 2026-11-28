<?php

namespace App\Enum;

/**
 * Niveau d'une sortie (pastille de la carte et de la fiche).
 */
enum OutingLevel: string
{
    case Discovery = 'discovery';
    case Initiated = 'initiated';
    case Confirmed = 'confirmed';
    case Expert = 'expert';

    public function label(?string $locale = 'fr'): string
    {
        if ('en' === $locale) {
            return match ($this) {
                self::Discovery => 'Beginner',
                self::Initiated => 'Intermediate',
                self::Confirmed => 'Advanced',
                self::Expert => 'Expert',
            };
        }

        return match ($this) {
            self::Discovery => 'Découverte',
            self::Initiated => 'Initié',
            self::Confirmed => 'Confirmé',
            self::Expert => 'Expert',
        };
    }

    // nombre de pictos pleins sur 4 (jauge de niveau)
    public function rank(): int
    {
        return match ($this) {
            self::Discovery => 1,
            self::Initiated => 2,
            self::Confirmed => 3,
            self::Expert => 4,
        };
    }
}
