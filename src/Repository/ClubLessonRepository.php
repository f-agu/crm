<?php

namespace App\Repository;

use App\Entity\ClubLesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method ClubLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClubLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClubLesson[]    findAll()
 * @method ClubLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubLessonRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClubLesson::class);
    }

    /**
     * @return ClubLesson[] Returns an array of ClubLesson objects
     */
    
    public function findByClubUuid($clubUuid)
    {
		$sql = "SELECT *"
				." FROM club_lesson les"
				."  JOIN club c ON c.id = les.club_id"
				."  JOIN club_location loc ON les.club_location_id = loc.id"
				." WHERE c.uuid = :clubUuid";
		$rsm = new ResultSetMappingBuilder($this->getEntityManager());
		$rsm->addRootEntityFromClassMetadata('App\Entity\ClubLesson', 'les');
		$rsm->addJoinedEntityFromClassMetadata('App\Entity\Club', 'c', 'les', 'club', ['id' => 'club_id', 'uuid' => 'club.uuid']);
		//$rsm->addJoinedEntityFromClassMetadata('App\Entity\ClubLocation', 'loc', 'les', 'club_location', ['id' => 'club_location_id', 'uuid' => 'club_location_uuid', 'name' => 'club_location_name']);
		//$rsm->addScalarResult('club_id', 'les');
		$query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
		$query->setParameter('clubUuid', $clubUuid);
		return $query->getResult();
	}
    

    /*
    public function findOneBySomeField($value): ?ClubLesson
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
