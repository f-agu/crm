<?php
namespace App\Dao;

use Doctrine\ORM\EntityManagerInterface;

class SearchDao
{

    private $em;
    private $authorizationChecker;
    
    public function __construct(EntityManagerInterface $em, $authorizationChecker)
    {
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
    }
    
    public function search($query) {
        $paramLC = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC')
            ->transliterate($query);
        $paramLC = '%'.mb_strtolower($paramLC).'%';
        $sql = "SELECT *"
               ." FROM (";
        
        $sql = $sql
               .") t"
               ." ORDER BY 3" // variable : ASC / DESC
               ." LIMIT 0, 10"; // variable
    }
}

