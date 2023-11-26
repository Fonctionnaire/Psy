<?php

namespace App\Repository;

use App\Entity\VolunteerInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VolunteerInfo>
 *
 * @method VolunteerInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method VolunteerInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method VolunteerInfo[]    findAll()
 * @method VolunteerInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolunteerInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VolunteerInfo::class);
    }

    //    /**
    //     * @return VolunteerInfo[] Returns an array of VolunteerInfo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VolunteerInfo
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
