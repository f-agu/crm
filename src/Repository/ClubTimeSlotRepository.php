<?php

namespace App\Repository;

use App\Entity\ClubTimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClubTimeSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClubTimeSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClubTimeSlot[]    findAll()
 * @method ClubTimeSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubTimeSlotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClubTimeSlot::class);
    }

    // /**
    //  * @return ClubTimeSlot[] Returns an array of ClubTimeSlot objects
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
    public function findOneBySomeField($value): ?ClubTimeSlot
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
