<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\ClubLesson;
use App\Entity\ClubLocation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClubsFixtures extends Fixture
{
    
	public function load(ObjectManager $manager)
	{
		//$manager->persist($this->createClub("Koryo", 100));
		//$manager->persist($this->createItem("documents", 200));
		//$manager->flush();
	}
    
	private function createClub($name, $logo, $website_url, $facebook_url, $mailing_list, $active = true)
	{
		$club = new Club();
		$club->setName($name);
		$club->setLogo($logo);
		$club->setWebsiteUrl($website_url);
		$club->setFacebookUrl($facebook_url);
		$club->setMailingList($mailing_list);
		$club->setActive($active);
		return $club;
	}
	
	private function createClubLesson($club, $location, $discipline, $age_level, $point = 1)
	{
		$clubloc = new ClubLesson();
		$clubloc->setClub($club);
		$clubloc->setClubLocation($location);
		$clubloc->setDiscipline($discipline);
		$clubloc->setPoint($point);
		$clubloc->setAgeLevel($age_level);
		return $clubloc;
	}
	
	private function createClubLocation($name, $address, $city, $zipcode, $county, $country = "France")
	{
		$clubloc = new ClubLocation();
		$clubloc->setName($name);
		$clubloc->setAddress($address);
		$clubloc->setCity($city);
		$clubloc->setZipcode($zipcode);
		$clubloc->setCounty($county);
		$clubloc->setCountry($country);
		return $clubloc;
	}
}
