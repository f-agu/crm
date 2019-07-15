<?php

namespace App\Repository;

use App\Entity\ClubLessonTimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClubLessonTimeSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClubLessonTimeSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClubLessonTimeSlot[]    findAll()
 * @method ClubLessonTimeSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubLessonTimeSlotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClubLessonTimeSlot::class);
    }

    // /**
    //  * @return ClubLessonTimeSlot[] Returns an array of ClubLessonTimeSlot objects
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
    public function findOneBySomeField($value): ?ClubLessonTimeSlot
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
