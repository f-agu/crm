<?php

namespace App\Service;

use App\Model\UserCreate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use App\Entity\User;
use App\Util\DateUtils;
use App\Util\DiffTool;
use App\Entity\UserHistory;
use App\Entity\Account;
use App\Util\TreeWalker;


class UserService
{

	private $em;

	public function __construct(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	public function create(Account $modifierAccount, UserCreate $userCreate)
	{
		$user = new User();
		$user->setLastname($userCreate->getLastname());
		$user->setFirstname($userCreate->getFirstname());
		$user->setBirthday(DateUtils::parseFrenchToDateTime($userCreate->getBirthday()));
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
		
		// history
		$elements = DiffTool::toArray($user);
		$treewalker = new TreeWalker(["debug" => false, "returntype" => "array"]);
		$diffArray = $treewalker->getdiff($elements, []);
		
		foreach (UserHistory::diffToHistories($diffArray, $modifierAccount, $user) as $uh) {
			$this->em->persist($uh);
			$this->em->flush();
		}
		
		return $user;
		//return ['u'=> $user, 'e' => $elements];
	}
	
	public function __destruct()
	{
		$this->em = null;
	}
	

}