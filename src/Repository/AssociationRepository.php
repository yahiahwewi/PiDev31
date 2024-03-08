<?php

namespace App\Repository;

use App\Entity\Association;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Association>
 *
 * @method Association|null find($id, $lockMode = null, $lockVersion = null)
 * @method Association|null findOneBy(array $criteria, array $orderBy = null)
 * @method Association[]    findAll()
 * @method Association[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Association::class);
    }

    /**
     * Recherche les associations par nom.
     *
     * @param string|null $searchTerm Le terme de recherche
     * @return Association[] Les associations correspondant au terme de recherche
     */
    public function findBySearchTerm(?string $searchTerm): array
    {
        if (!$searchTerm) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('a')
            ->where('a.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }


    public function findassociationbynom($nom)

    {

        return $this->createQueryBuilder('arb')

            ->where('arb.nom LIKE :nom')

            ->setParameter('nom', '%'.$nom.'%')

            ->getQuery()

            ->getResult();

    } 

    
public function triass()
{
    return $this->createQueryBuilder('association')
        ->orderBy('association.nom', 'ASC')
        ->getQuery()
        ->getResult();
}


public function trides()
{
    return $this->createQueryBuilder('association')
        ->orderBy('association.nom', 'DESC')
        ->getQuery()
        ->getResult();
}
}
