<?php

namespace App\Repository;

use App\Entity\ActivityGroup;
use App\Enum\PricingSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityGroup>
 */
class ActivityGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityGroup::class);
    }

    /**
     * Groupes avec leurs disciplines (une seule requête), ceux de la saison en cours en tête.
     *
     * @return ActivityGroup[]
     */
    public function findForMenu(?PricingSeason $currentSeason = null): array
    {
        $groups = $this->createQueryBuilder('g')
            ->leftJoin('g.disciplines', 'd')->addSelect('d')
            ->orderBy('g.position', 'ASC')->addOrderBy('g.id', 'ASC')
            ->addOrderBy('d.position', 'ASC')->addOrderBy('d.id', 'ASC')
            ->getQuery()->getResult();

        // tri stable : la saison en cours d'abord, puis l'ordre de l'admin
        if ($currentSeason) {
            usort($groups, fn (ActivityGroup $a, ActivityGroup $b) => ($b->getSeason() === $currentSeason) <=> ($a->getSeason() === $currentSeason));
        }

        return array_values(array_filter($groups, fn (ActivityGroup $g) => $g->getPublishedDisciplines()));
    }
}
