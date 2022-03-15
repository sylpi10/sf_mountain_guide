<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    public function findByIsAccepted($post)
    {
        return $this->createQueryBuilder('c')
            ->where('c.isAccepted = :isAccepted')
            ->setParameter('isAccepted', true)
            ->andWhere('c.post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->execute();
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */

    public function countIsAccepted($post)
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.isAccepted)')
            ->where('c.isAccepted = :isAccepted')
            ->setParameter('isAccepted', true)
            ->andWhere('c.post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->execute();
    }


    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
