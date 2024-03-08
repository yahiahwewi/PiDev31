<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\OptimisticLockException;

use Doctrine\ORM\ORMException;

/**
 * @extends ServiceEntityRepository<Conference>
 *
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findAll()
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

//    /**
//     * @return Conference[] Returns an array of Conference objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conference
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function trieamirr()
    {
        return $this->createQueryBuilder('conference')
            ->orderBy('conference.lieu', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**

     * return Conference[]

     */

     public function findlieubytype($lieu)

     {
 
         return $this->createQueryBuilder('arb')
 
             ->where('arb.lieu LIKE :lieu')
 
             ->setParameter('lieu', '%'.$lieu.'%')
 
             ->getQuery()
 
             ->getResult();
 
     } 







      /**
     * Recherche les associations par nom.
     *
     * @param string|null $searchTerm Le terme de recherche
     * @return Conference[] Les associations correspondant au terme de recherche
     */
    public function findBySearchTerm(?string $searchTerm): array
    {
        if (!$searchTerm) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('a')
            ->where('a.lieu LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }


}
