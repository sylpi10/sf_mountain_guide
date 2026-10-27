<?php

namespace App\Repository;

use App\Entity\AreaPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AreaPage>
 */
class AreaPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AreaPage::class);
    }

    // page dont le slug FR ou EN vaut $slug
    public function findOneByAnySlug(string $slug, bool $publishedOnly = true): ?AreaPage
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.slug = :slug OR p.slugEn = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1);
        if ($publishedOnly) {
            $qb->andWhere('p.isPublished = true');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return AreaPage[]
     */
    public function findPublished(): array
    {
        return $this->findBy(['isPublished' => true], ['id' => 'ASC']);
    }
}
