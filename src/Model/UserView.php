<?php

namespace App\Model;

use App\Entity\User;
use App\Util\DateUtils;

class UserView
{
    private $uuid;
	private $lastname;
	private $firstname;
	private $birthday;
	private $sex;
	private $address;
	private $zipcode;
	private $city;
	private $phone;
	private $phone_emergency;
	private $nationality;
	private $mails;
	private $created;
	
	public function __construct(User $user)
	{
	    $this->uuid = $user->getUuid();
	    $this->lastname = $user->getLastname();
	    $this->firstname = $user->getFirstname();
	    $this->birthday = DateUtils::toArray($user->getBirthday());
	    $this->sex = $user->getSex();
	    $this->address = $user->getAddress();
	    $this->zipcode = $user->getZipcode();
	    $this->city = $user->getCity();
	    $this->phone = $user->getPhone();
	    $this->phone_emergency = $user->getPhoneEmergency();
	    $this->nationality = $user->getNationality();
	    $this->mails = $user->getMails();
	    $this->created = DateUtils::toArray($user->getCreated());
	}
	
	public function getUuid()
	{
	    return $this->uuid;
	}
	
	public function getLastname()
	{
		return $this->lastname;
	}

	public function getFirstname()
	{
		return $this->firstname;
	}

	public function getBirthday()
	{
	    return $this->birthday;
	}

	public function getSex(): ?string
	{
	    return $this->sex;
	}
		
	public function getAddress(): ?string
	{
	    return $this->address;
	}
	
	public function getZipcode(): ?string
	{
	    return $this->zipcode;
	}
	
	public function getCity(): ?string
	{
	    return $this->city;
	}
	
	public function getPhone(): ?string
	{
	    return $this->phone;
	}
	
	public function getPhoneEmergency(): ?string
	{
	    return $this->phone_emergency;
	}
		
	public function getNationality(): ?string
	{
	    return $this->nationality;
	}
	
	public function getMails(): ?string
	{
	    return $this->mails;
	}
	
	public function getCreated()
	{
	    return $this->created;
	}
	
	public function jsonSerialize()
	{
	    return
	    [
	        'uuid'   => $this->getUuid(),
	        'lastname' => $this->getLastname(),
	        'firstname' => $this->getFirstname(),
	        'sex' => $this->getSex(),
	        'birthday' => $this->getBirthday(),
	        'address' => $this->getAddress(),
	        'city' => $this->getCity(),
	        'mails' => $this->getMails(),
	        'nationality' => $this->getNationality(),
	        'phone' => $this->getPhone(),
	        'phone_emergency' => $this->getPhoneEmergency(),
	        'zipcode' => $this->getZipcode(),
	        'created' => $this->getCreated(),
	    ];
	}
}