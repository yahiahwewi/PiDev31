<?php

namespace App\Repository;

use App\Entity\DonationsList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DonationsList>
 *
 * @method DonationsList|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonationsList|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonationsList[]    findAll()
 * @method DonationsList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonationsListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonationsList::class);
    }

//    /**
//     * @return DonationsList[] Returns an array of DonationsList objects
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

//    public function findOneBySomeField($value): ?DonationsList
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
