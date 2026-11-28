<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Enum\PricingSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Outing>
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    /**
     * Sorties publiées et mises en avant dont la discipline (publiée) est rangée dans un groupe de la saison donnée.
     *
     * @return Outing[]
     */
    public function findFeatured(PricingSeason $season, int $limit = 3): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.discipline', 'd')->addSelect('d')
            ->join('d.activityGroup', 'g')
            ->andWhere('o.isPublished = true AND o.featuredOnHome = true AND d.isPublished = true')
            ->andWhere('g.season = :season')->setParameter('season', $season)
            ->orderBy('g.position', 'ASC')->addOrderBy('d.position', 'ASC')->addOrderBy('o.position', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }

    /**
     * @return Outing[] autres sorties publiées de la même discipline
     */
    public function findSimilar(Outing $outing, int $limit = 3): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.discipline = :discipline AND o.id != :id AND o.isPublished = true')
            ->setParameter('discipline', $outing->getDiscipline())
            ->setParameter('id', $outing->getId())
            ->orderBy('o.position', 'ASC')->addOrderBy('o.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }
}
