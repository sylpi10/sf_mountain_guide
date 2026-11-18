<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogCategory>
 */
class BlogCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }

    /**
     * Catégories ayant au moins un article, dans l'ordre d'affichage, avec leur nombre d'articles.
     *
     * @return list<array{category: BlogCategory, count: int}>
     */
    public function findUsedWithCount(): array
    {
        $rows = $this->createQueryBuilder('c')
            ->select('c AS category', 'COUNT(b.id) AS count')
            ->innerJoin(Blog::class, 'b', 'WITH', 'b.category = c')
            ->groupBy('c.id')
            ->orderBy('c.position', \SortDirection::Ascending)
            ->addOrderBy('c.name', \SortDirection::Ascending)
            ->getQuery()
            ->getResult();

        return array_map(fn (array $row) => ['category' => $row['category'], 'count' => (int) $row['count']], $rows);
    }
}
