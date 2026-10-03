<?php

namespace App\Repository;

use App\Entity\PricingPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PricingPage>
 */
class PricingPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PricingPage::class);
    }

    public function findPage(): ?PricingPage
    {
        return $this->findOneBy([], ['id' => 'ASC']);
    }
}
