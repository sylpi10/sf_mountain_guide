<?php

namespace App\Repository;

use App\Entity\SiteText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SiteText>
 */
class SiteTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteText::class);
    }

    /**
     * @return array<string, SiteText>
     */
    public function findAllIndexedBySlot(): array
    {
        return $this->createQueryBuilder('t', 't.slot')
            ->getQuery()
            ->getResult();
    }
}
