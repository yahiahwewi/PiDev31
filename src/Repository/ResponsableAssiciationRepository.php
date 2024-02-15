<?php

namespace App\Repository;

use App\Entity\ResponsableAssiciation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResponsableAssiciation>
 *
 * @method ResponsableAssiciation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResponsableAssiciation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResponsableAssiciation[]    findAll()
 * @method ResponsableAssiciation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponsableAssiciationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResponsableAssiciation::class);
    }

//    /**
//     * @return ResponsableAssiciation[] Returns an array of ResponsableAssiciation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResponsableAssiciation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
