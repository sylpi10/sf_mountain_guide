<?php

namespace App\Service;

use App\Enum\PricingSeason;
use App\Repository\PricingPageRepository;

/**
 * Saison en cours selon les mois réglés en admin (Tarifs > Saison et introduction) :
 * onglet ouvert de la page Tarifs, mise en avant de l'accueil, ordre du menu Activités.
 */
class SeasonResolver
{
    private ?PricingSeason $current = null;

    public function __construct(private PricingPageRepository $pricingRepo)
    {
    }

    public function current(): PricingSeason
    {
        if (!$this->current) {
            $page = $this->pricingRepo->findPage();
            $this->current = $page
                ? PricingSeason::current($page->getWinterFromMonth(), $page->getSummerFromMonth())
                : PricingSeason::current();
        }

        return $this->current;
    }
}
