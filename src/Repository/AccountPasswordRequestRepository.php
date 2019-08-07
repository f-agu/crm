<?php

namespace App\Repository;

use App\Entity\AccountPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method AccountPasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountPasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountPasswordRequest[]	findAll()
 * @method AccountPasswordRequest[]	findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountPasswordRequestRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, AccountPasswordRequest::class);
	}

	public function buildByValidLogin($login): ?AccountPasswordRequest
	{
		$sql = "SELECT a.*"
			  ." FROM user u"
			  ."  JOIN account a ON u.id = a.user_id"
			  ."  LEFT JOIN account_password_request apr ON apr.account_id = a.id"
			  ." WHERE login = :login"
			  ."  AND blacklist_date IS NULL"
			  ."  AND a.has_access"
			  ."  AND apr.id IS NULL";

		$rsm = new ResultSetMappingBuilder($this->getEntityManager());
		$rsm->addRootEntityFromClassMetadata('App\Entity\Account', 'a');
		$query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
		$query->setParameter('login', $login);
		$accounts = $query->getResult();
		if(count($accounts) == 1) {
			$request = new AccountPasswordRequest();
			$request->setAccount($accounts[0]);
			$this->getEntityManager()->persist($request);
			$this->getEntityManager()->flush();
			return $request;
		}
		return null;
	}


	// /**
	//  * @return AccountPasswordRequest[] Returns an array of AccountPasswordRequest objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('a')
			->andWhere('a.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('a.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?AccountPasswordRequest
	{
		return $this->createQueryBuilder('a')
			->andWhere('a.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
