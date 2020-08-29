<?php

namespace App\Repository;

use App\Entity\Club;
use App\Model\ClubLocationView;
use App\Model\ClubView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\ClubLocation;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
	private $registry;

	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Club::class);
		$this->registry = $registry;
	}


	public function findAllActiveWithLocations()
	{
		$clubs = $this->findBy(['active' => true]);
		return $this->registry->getManager()
					->getRepository(ClubLocation::class)
					->findByClubs($clubs);
	}


	/*public function findAllActiveGroupedWithCitiesOLD() //: ?ClubDTO
	{
		$sql = "SELECT id, name, group_concat(city SEPARATOR  ', ') AS cities"
		      ." FROM ("
		      ."  SELECT c.id, c.name, loc.city"
		      ."   FROM club c"
		      ."    JOIN club_lesson les ON c.id = les.club_id"
		      ."    JOIN club_location loc ON les.club_location_id = loc.id"
		      ."   WHERE c.active"
		      ."   GROUP BY 1, 2, 3"
		      ."  ) t"
		      ." GROUP BY 1, 2";

		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('name', 'name');
		$rsm->addScalarResult('cities', 'cities');

		$stmt = $this->getEntityManager()->createNativeQuery($sql, $rsm);

		return $stmt->getResult();
	}*/


    // /**
    //  * @return Club[] Returns an array of Club objects
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
    public function findOneBySomeField($value): ?Club
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
