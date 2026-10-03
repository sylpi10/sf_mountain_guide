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

    public function findPublishedBySlug(string $slug): ?AreaPage
    {
        return $this->findOneBy(['slug' => $slug, 'isPublished' => true]);
    }

    /**
     * @return AreaPage[]
     */
    public function findPublished(): array
    {
        return $this->findBy(['isPublished' => true], ['id' => 'ASC']);
    }
}
