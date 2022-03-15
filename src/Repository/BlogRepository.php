<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function findByDate()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.updated_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    // public function countIsAccepted()
    // {
    //     return $this->createQueryBuilder('c')
    //         ->select('count(c.isAccepted)')
    //         ->where('c.isAccepted = :isAccepted')
    //         ->setParameter('isAccepted', true)
    //         ->join('')
    //         ->andWhere('c.post = :val')
    //         ->setParameter('val', $post)
    //         ->getQuery()
    //         ->execute();
    // }

    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
