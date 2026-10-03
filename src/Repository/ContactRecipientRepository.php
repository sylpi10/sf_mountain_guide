<?php

namespace App\Repository;

use App\Entity\ContactRecipient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactRecipient>
 */
class ContactRecipientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactRecipient::class);
    }

    /**
     * @return string[]
     */
    public function findActiveEmails(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.email')
            ->where('r.isActive = true')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
