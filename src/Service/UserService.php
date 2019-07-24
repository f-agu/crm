<?php

namespace App\Service;

use App\Model\UserCreate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use App\Entity\User;


class UserService
{

    private $em;

	public function __construct(ObjectManager $em)
	{
		$this->em = $em;
	}
    
	public function create(UserCreate $userCreate)
	{
	    $user = new User();
	    $user->setLastname($userCreate->getLastname());
	    $user->setFirstname($userCreate->getFirstname());
	    $user->setBirthday(new \DateTime($userCreate->parseFrBirthday()));
	    $this->em->persist($user);
	    $this->em->flush();
		
		return array(
		    'id' => $user->getId()
            );
	}
    
	public function __destruct()
	{
		$this->em = null;
	}
    

}