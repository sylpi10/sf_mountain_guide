<?php

namespace App\Repository;

use App\Entity\PricingSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PricingSection>
 */
class PricingSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PricingSection::class);
    }

    /**
     * Sections avec leurs blocs et lignes, en une requête, dans l'ordre d'affichage.
     *
     * @return PricingSection[]
     */
    public function findForPage(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.groups', 'g')->addSelect('g')
            ->leftJoin('g.lines', 'l')->addSelect('l')
            ->orderBy('s.position')->addOrderBy('s.id')
            ->addOrderBy('g.position')->addOrderBy('g.id')
            ->addOrderBy('l.position')->addOrderBy('l.id')
            ->getQuery()
            ->getResult();
    }
}
