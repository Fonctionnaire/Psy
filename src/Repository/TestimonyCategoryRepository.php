<?php

namespace App\Repository;

use App\Entity\TestimonyCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestimonyCategory>
 *
 * @method TestimonyCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestimonyCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestimonyCategory[]    findAll()
 * @method TestimonyCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonyCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestimonyCategory::class);
    }

//    /**
//     * @return TestimonyCategory[] Returns an array of TestimonyCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TestimonyCategory
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
