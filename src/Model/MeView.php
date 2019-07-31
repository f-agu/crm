<?php

namespace App\Model;

use App\Entity\User;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use App\Entity\Account;

/**
 * @Serializer\XmlRoot("me")
 *
 * @Hateoas\Relation("self", href = "/api/me")
 */
class MeView extends UserView
{
	private $login;
	private $roles;
	private $grantedRoles;
	
	public function __construct(Account $account, User $user, $grantedRoles)
	{
		parent::__construct($user);
		$this->login = $account->getLogin();
		$this->roles = $account->getRoles();
		$this->grantedRoles = $grantedRoles;
	}
	
	public function getLogin()
	{
		return $this->login;
	}
	
	public function getRoles()
	{
		return $this->roles;
	}
	
	public function getGrantedRoles()
	{
		return $this->grantedRoles;
	}
	
}