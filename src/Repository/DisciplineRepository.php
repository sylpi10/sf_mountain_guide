<?php

namespace App\Repository;

use App\Entity\Discipline;
use App\Enum\PricingSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Discipline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discipline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discipline[]    findAll()
 * @method Discipline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisciplineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discipline::class);
    }

    /**
     * @return Discipline[] disciplines publiées, dans l'ordre des groupes puis de l'admin
     */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.activityGroup', 'g')
            ->andWhere('d.isPublished = true')
            ->orderBy('g.position', 'ASC')->addOrderBy('d.position', 'ASC')->addOrderBy('d.id', 'ASC')
            ->getQuery()->getResult();
    }

    /**
     * Disciplines mises en avant sur l'accueil pour la saison donnée ; à défaut, toutes celles de la saison.
     *
     * @return Discipline[]
     */
    public function findFeatured(PricingSeason $season): array
    {
        $qb = $this->createQueryBuilder('d')
            ->join('d.activityGroup', 'g')
            ->andWhere('d.isPublished = true AND g.season = :season')->setParameter('season', $season)
            ->orderBy('g.position', 'ASC')->addOrderBy('d.position', 'ASC')->addOrderBy('d.id', 'ASC');

        $featured = (clone $qb)->andWhere('d.featuredOnHome = true')->getQuery()->getResult();

        return $featured ?: $qb->getQuery()->getResult();
    }
}
