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
	    $user->setSex($userCreate->getSex());
	    $user->setAddress($userCreate->getAddress());
	    $user->setZipcode($userCreate->getZipcode());
	    $user->setCity($userCreate->getCity());
	    $user->setPhone($userCreate->getPhone());
	    $user->setPhoneEmergency($userCreate->getPhoneEmergency());
	    $user->setNationality($userCreate->getNationality());
	    $user->setMails($userCreate->getMails());
	    $this->em->persist($user);
	    $this->em->flush();
		
		return array(
		    'id' => $user->getId(),
		    'lastname' => $user->getLastname(),
		    'firstname' => $user->getFirstname(),
            );
	}
    
	public function __destruct()
	{
		$this->em = null;
	}
    

}