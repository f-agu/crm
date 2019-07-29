<?php
namespace App\Dao;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account;

class SearchDao
{

    private $em;
    private $authorizationChecker;
    
    public function __construct(EntityManagerInterface $em, $authorizationChecker)
    {
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
    }
    
    public function search($query, ?Account $connectedAccount, $offset = 0, $limit = 10) {
        $paramLC = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC')
            ->transliterate($query);
        $paramLC = '%'.mb_strtolower($paramLC).'%';
        
        $unions = array($this::inClub());
        $params = array('query' => $paramLC);
        if($this->authorizationChecker->isGranted("ROLE_ADMIN")) {
            array_push($unions, $this::inUserAll());
        } elseif($this->authorizationChecker->isGranted("ROLE_TEACHER")) {
            array_push($unions, $this::inUserInMyClubs());
            $params['teacherAccountId'] = $connectedAccount->getId();
        }
        if($this->authorizationChecker->isGranted("ROLE_ADMIN")) {
            array_push($unions, $this::inAccount());
        }
        
        $sql = "SELECT *"
               ." FROM ("
               .implode(" UNION ", $unions)
               .") t"
               ." ORDER BY 4" // variable : ASC / DESC
               ." LIMIT ".$offset.", ".$limit;
        
        $stmt = $this->em->getConnection()->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }
    
    
    private function inUserAll()
    {
        return "SELECT 'user' AS type, id, uuid, CONCAT(lastname, ' ', firstname) AS name"
              ." FROM user"
              ." WHERE (remove_accents(lower(lastname)) LIKE :query"
              ."    OR remove_accents(lower(firstname)) LIKE :query"
              ."    OR remove_accents(lower(mails)) LIKE :query"
              ."    OR remove_accents(lower(address)) LIKE :query"
              ."    OR remove_accents(lower(city)) LIKE :query"
              ."    OR remove_accents(lower(nationality)) LIKE :query)"
              ;
    }
    
    private function inUserInMyClubs()
    {
        return "SELECT 'user' AS type, u.id, u.uuid, CONCAT(u.lastname, ' ', u.firstname) AS name"
              ." FROM account a"
              ."  JOIN user teacher ON a.user_id = teacher.id"
              ."  JOIN user_lesson_subscribe tsubsc ON teacher.id = tsubsc.user_id"
              ."  JOIN user_lesson_subscribe usubsc ON tsubsc.lesson_id = usubsc.lesson_id"
              ."  JOIN user u ON u.id = usubsc.user_id"
              ." WHERE a.id = :teacherAccountId"
              ."   AND (remove_accents(lower(u.lastname)) LIKE :query"
              ."     OR remove_accents(lower(u.firstname)) LIKE :query"
              ."     OR remove_accents(lower(u.mails)) LIKE :query"
              ."     OR remove_accents(lower(u.address)) LIKE :query"
              ."     OR remove_accents(lower(u.city)) LIKE :query"
              ."     OR remove_accents(lower(u.nationality)) LIKE :query"
              ."   	 )"
              ." GROUP BY 1, 2, 3, 4"
              ;
    }
    private function inAccount()
    {
        return "SELECT 'account' AS type, a.id, u.uuid, CONCAT(lastname, ' ', firstname) AS name"
               ." FROM account a"
               ."  JOIN user u ON a.user_id = u.id"
               ." WHERE remove_accents(lower(login)) LIKE :query"
               ." GROUP BY 1, 2, 3, 4"
               ;
    }
    
    private function inClub()
    {
        return "SELECT 'club' AS type, c.id, c.uuid, c.name"
              ." FROM club c"
              ."  JOIN club_lesson cles ON (cles.club_id = c.id AND c.active)"
              ."  JOIN club_location cl ON cles.club_location_id = cl.id"
              ." WHERE remove_accents(lower(c.name)) LIKE :query"
              ."    OR remove_accents(lower(cl.name)) LIKE :query"
              ."    OR remove_accents(lower(cl.city)) LIKE :query"
              ."    OR remove_accents(lower(cl.address)) LIKE :query"
              ."    OR remove_accents(lower(cl.zipcode)) LIKE :query"
              ."    OR remove_accents(lower(cl.county)) LIKE :query"
              ."    OR remove_accents(lower(cl.country)) LIKE :query"
              ."    OR remove_accents(lower(cles.discipline)) LIKE :query"
              ."    OR remove_accents(lower(cles.age_level)) LIKE :query"
              ." GROUP BY 1, 2, 3, 4"
              ;
    }
}

