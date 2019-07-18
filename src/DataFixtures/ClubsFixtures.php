<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\ClubLesson;
use App\Entity\ClubLocation;
use App\Entity\ClubTimeSlot;
use App\Entity\ClubLessonTimeSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClubsFixtures extends Fixture
{
    
	public function load(ObjectManager $manager)
	{
		// ======================== Bry
		$club             = $this->createClub('Koryo', 'bry_sur_marne.gif', 'http://www.taekwondobry.fr');
		$location         = $this->createLocation('Ecole Henri Cahn', '26 Boulevard Général Gallieni', 'Bry-sur-Marne', '94360', '94');
		$lessonTKiDAll    = $this->createLesson($club, $location, 'Taekwonkido', 'Tous niveaux');
		$lessonTKDAll     = $this->createLesson($club, $location, 'Taekwondo', 'Tous niveaux');
		$lessonTKDChild   = $this->createLesson($club, $location, 'Taekwondo', 'Baby / enfants');
		$lessonHKD        = $this->createLesson($club, $location, 'Hapkido', 'Tous niveaux');
		$lessonHKDBlkBelt = $this->createLesson($club, $location, 'Hapkido', 'Ceintures noires');
		$timeslotTues19   = $this->createTimeSlot('tuesday', new \DateTime('19:00'), new \DateTime('19:45'));
		$timeslotTues1945 = $this->createTimeSlot('tuesday', new \DateTime('19:45'), new \DateTime('20:30'));
		$timeslotWed19    = $this->createTimeSlot('wednesday', new \DateTime('19:00'), new \DateTime('20:00'));
		$timeslotThur19   = $this->createTimeSlot('thursday', new \DateTime('19:00'), new \DateTime('19:45'));
		$timeslotThur1945 = $this->createTimeSlot('thursday', new \DateTime('19:45'), new \DateTime('20:30'));
		$timeslotThur1945 = $this->createTimeSlot('thursday', new \DateTime('20:30'), new \DateTime('21:00'));
		// Tuesday
		$manager->persist($this->createLinkLessonTimeSlot($lessonTKiDAll, $timeslotTues19));
		$manager->persist($this->createLinkLessonTimeSlot($lessonTKDAll, $timeslotTues1945));
		$manager->persist($this->createLinkLessonTimeSlot($lessonHKD, $timeslotTues1945));
		// Wednesday
		$manager->persist($this->createLinkLessonTimeSlot($lessonTKDChild, $timeslotWed19));
		// Thursday
		$manager->persist($this->createLinkLessonTimeSlot($lessonTKiDAll, $timeslotThur19));
		$manager->persist($this->createLinkLessonTimeSlot($lessonTKDAll, $timeslotThur1945));
		$manager->persist($this->createLinkLessonTimeSlot($lessonHKD, $timeslotThur1945));
		$manager->persist($this->createLinkLessonTimeSlot($lessonHKDBlkBelt, $timeslotThur1945));
	
		$manager->flush();
	}
    
	private function createClub($name, $logo, $website_url, $facebook_url = null, $mailing_list = null, $active = true)
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
	
	private function createLesson($club, $location, $discipline, $age_level, $point = 1)
	{
		$clubloc = new ClubLesson();
		$clubloc->setClub($club);
		$clubloc->setClubLocation($location);
		$clubloc->setDiscipline($discipline);
		$clubloc->setPoint($point);
		$clubloc->setAgeLevel($age_level);
		return $clubloc;
	}
	
	private function createLocation($name, $address, $city, $zipcode, $county, $country = "France")
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
	
	private function createTimeSlot($day_of_week, $start_time, $end_time)
	{
		$timeslot = new ClubTimeSlot();
		$timeslot->setDayOfWeek($day_of_week);
		$timeslot->setStartTime($start_time);
		$timeslot->setEndTime($end_time);
		return $timeslot;
	}
	
	private function createLinkLessonTimeSlot($lesson, $timeslot)
	{
		$lessontimeslot = new ClubLessonTimeSlot();
		$lessontimeslot->setClubLesson($lesson);
		$lessontimeslot->setClubTimeSlot($timeslot);
		return $lessontimeslot;
	}
}
