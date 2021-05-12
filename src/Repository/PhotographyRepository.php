<?php

namespace App\Repository;

use App\Entity\Photography;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Photography|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photography|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photography[]    findAll()
 * @method Photography[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotographyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photography::class);
    }

    // /**
    //  * @return Photography[] Returns an array of Photography objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Photography
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
