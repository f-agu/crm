<?php

namespace App\Model;

use App\Validator\Constraints as AcmeAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UserCreate
{
	/**
	 * @Assert\NotBlank
	 * @Assert\Type("string")
	 * @Assert\Length(min = 1, max = 255)
	 */
	private $lastname;
	
	/**
	 * @Assert\NotBlank
	 * @Assert\Type("string")
	 * @Assert\Length(min = 1, max = 255)
	 */
	private $firstname;
	
	/**
	 * @Assert\NotBlank
	 * @AcmeAssert\Birthday
	 */
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
	
	public function parseFrBirthday()
	{
	    if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $this->birthday, $matches)) {
	        return $matches[2].'/'.$matches[1].'/'.$matches[3];
	    }
	    return null;
	}
	
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

}