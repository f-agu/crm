<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserCreate
{
	/**
	 * @Assert\Type("string")
	 * @Assert\NotBlank
	 */
	private $lastname;
	
	private $firstname;
	
	private $birthday;

	public function getLastname()
	{
		return $this->lastname;
	}

	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}

	public function getFirstname()
	{
		return $this->firstname;
	}

	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
	}

	public function getBirthday()
	{
		return $this->birthday;
	}

	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

}