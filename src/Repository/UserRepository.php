<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findInMyClubs($accountId, $uuid = null, $offset = 0, $limit = 20)
    {
        $sql = "SELECT u.*"
              ." FROM account a"
              ."  JOIN user teacher ON a.user_id = teacher.id"
              ."  JOIN user_lesson_subscribe tsubsc ON teacher.id = tsubsc.user_id"
              ."  JOIN user_lesson_subscribe usubsc ON tsubsc.lesson_id = usubsc.lesson_id"
              ."  JOIN user u ON u.id = usubsc.user_id"
              ." WHERE a.id = :accountId";
        if($uuid) {
            $sql = $sql." AND u.uuid = :uuid";
        }
        $sql .= " ORDER BY lastname ASC, firstname ASC";
        $sql .= " LIMIT ".$offset.", ".$limit;

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\User', 'u');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('accountId', $accountId);
        if($uuid) {
            $query->setParameter('uuid', $uuid);
        }
        return $query->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/

}
