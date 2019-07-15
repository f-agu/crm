<?php

namespace App\Repository;

use App\Entity\ClubLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClubLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClubLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClubLocation[]    findAll()
 * @method ClubLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubLocationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClubLocation::class);
    }

    // /**
    //  * @return ClubLocation[] Returns an array of ClubLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClubLocation
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
