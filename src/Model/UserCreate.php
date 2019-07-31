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

	/**
	 * @Assert\NotBlank
	 * @Assert\Regex(pattern = "[F|M]")
	 */
	private $sex;
	
	/**
	 * @Assert\Length(max = 512)
	 */
	private $address;
	
	/**
	 * @Assert\Length(max = 32)
	 */
	private $zipcode;
	
	/**
	 * @Assert\Length(max = 255)
	 */
	private $city;
	
	/**
	 * @Assert\Length(max = 32)
	 */
	private $phone;
	
	/**
	 * @Assert\Length(max = 32)
	 */
	private $phone_emergency;
	
	/**
	 * @Assert\Length(max = 64)
	 */
	private $nationality;
	
	/**
	 * @Assert\Length(max = 512)
	 */
	private $mails;
	
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

	public function getSex(): ?string
	{
		return $this->sex;
	}
	
	public function setSex(string $sex): self
	{
		$this->sex = $sex;
		
		return $this;
	}

	
	public function getAddress(): ?string
	{
		return $this->address;
	}
	
	public function setAddress(?string $address): self
	{
		$this->address = $address;
		
		return $this;
	}
	
	public function getZipcode(): ?string
	{
		return $this->zipcode;
	}
	
	public function setZipcode(?string $zipcode): self
	{
		$this->zipcode = $zipcode;
		
		return $this;
	}
	
	public function getCity(): ?string
	{
		return $this->city;
	}
	
	public function setCity(?string $city): self
	{
		$this->city = $city;
		
		return $this;
	}
	
	public function getPhone(): ?string
	{
		return $this->phone;
	}
	
	public function setPhone(?string $phone): self
	{
		$this->phone = $phone;
		
		return $this;
	}
	
	public function getPhoneEmergency(): ?string
	{
		return $this->phone_emergency;
	}
	
	public function setPhoneEmergency(?string $phone_emergency): self
	{
		$this->phone_emergency = $phone_emergency;
		
		return $this;
	}
	
	public function getNationality(): ?string
	{
		return $this->nationality;
	}
	
	public function setNationality(?string $nationality): self
	{
		$this->nationality = $nationality;
		
		return $this;
	}
	
	public function getMails(): ?string
	{
		return $this->mails;
	}
	
	public function setMails(?string $mails): self
	{
		$this->mails = $mails;
		
		return $this;
	}
}