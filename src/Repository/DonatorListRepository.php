<?php

namespace App\Repository;

use App\Entity\DonatorList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DonatorList>
 *
 * @method DonatorList|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonatorList|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonatorList[]    findAll()
 * @method DonatorList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonatorListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonatorList::class);
    }

//    /**
//     * @return DonatorList[] Returns an array of DonatorList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DonatorList
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
