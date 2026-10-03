<?php

namespace App\Repository;

use App\Entity\LegalPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LegalPage>
 */
class LegalPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalPage::class);
    }

    public function findPublishedBySlug(string $slug): ?LegalPage
    {
        return $this->findOneBy(['slug' => $slug, 'isPublished' => true]);
    }

    /**
     * @return LegalPage[]
     */
    public function findPublished(): array
    {
        return $this->findBy(['isPublished' => true], ['id' => 'ASC']);
    }
}
