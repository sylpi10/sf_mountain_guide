<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Les adresses FR et EN d'une page secteur doivent être libres : ni prises par une page fixe
 * du site (/tarifs, /blog, /cgv…), ni par une autre page secteur (dans une langue ou l'autre).
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class AreaPageSlugs extends Constraint
{
    public string $routeMessage = 'Cette adresse est déjà utilisée par une autre page du site.';
    public string $areaMessage = 'Cette adresse est déjà utilisée par la page secteur « {{ name }} ».';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
