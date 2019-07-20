<?php

namespace App\Repository;

use App\Entity\UserLessonSubscribe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserLessonSubscribe|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLessonSubscribe|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLessonSubscribe[]    findAll()
 * @method UserLessonSubscribe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLessonSubscribeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLessonSubscribe::class);
    }

    // /**
    //  * @return UserLessonSubscribe[] Returns an array of UserLessonSubscribe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserLessonSubscribe
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
