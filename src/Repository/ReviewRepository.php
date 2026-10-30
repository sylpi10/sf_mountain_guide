<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Avis publiés, les plus récents d'abord.
     *
     * @return Review[]
     */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.published = true')
            ->orderBy('r.reviewedAt', \SortDirection::Descending)
            ->addOrderBy('r.id', \SortDirection::Descending)
            ->getQuery()
            ->getResult();
    }
}
