<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

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

    // articles les plus récents d'abord, éventuellement d'une seule catégorie (requête passée au paginateur)
    public function queryByDate(?BlogCategory $category = null): Query
    {
        $qb = $this->createQueryBuilder('b')
            ->addSelect('c')
            ->leftJoin('b.category', 'c')
            ->orderBy('b.updated_at', \SortDirection::Descending);
        if (null !== $category) {
            $qb->andWhere('b.category = :category')->setParameter('category', $category);
        }

        return $qb->getQuery();
    }

    /*
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
