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
		$club           = $this->createClub('Koryo', 'bry_sur_marne.gif', 'http://www.taekwondobry.fr');
		$location       = $this->createLocation('Ecole Henri Cahn', '26 boulevard Général Gallieni', 'Bry-sur-Marne', '94360', '94');
		// Tuesday
		$manager->persist($this->createLesson($club, $location, 'Taekwonkido', 'Tous niveaux', 'tuesday', new \DateTime('19:00'), new \DateTime('19:45')));
		$manager->persist($this->createLesson($club, $location, 'Taekwondo', 'Tous niveaux', 'tuesday', new \DateTime('19:45'), new \DateTime('20:30')));
		$manager->persist($this->createLesson($club, $location, 'Hapkido', 'Tous niveaux', 'tuesday', new \DateTime('19:45'), new \DateTime('20:30')));
		// Wednesday
		$manager->persist($this->createLesson($club, $location, 'Taekwonkido', 'Baby / enfants', 'wednesday', new \DateTime('19:00'), new \DateTime('20:00')));
		// Thursday
		$manager->persist($this->createLesson($club, $location, 'Taekwonkido', 'Tous niveaux', 'thursday', new \DateTime('19:00'), new \DateTime('19:45')));
		$manager->persist($this->createLesson($club, $location, 'Taekwondo', 'Tous niveaux', 'thursday', new \DateTime('19:45'), new \DateTime('20:30')));
		$manager->persist($this->createLesson($club, $location, 'Hapkido', 'Tous niveaux', 'thursday', new \DateTime('19:45'), new \DateTime('20:30')));
		$manager->persist($this->createLesson($club, $location, 'Hapkido', 'Ceintures noires', 'thursday', new \DateTime('20:30'), new \DateTime('21:00')));
		$manager->persist($this->createLesson($club, $location, 'Gumdo', 'Tous niveaux', 'thursday', new \DateTime('21:00'), new \DateTime('22:00')));
		$manager->flush();
		
		// ======================== Chelles
		$club           = $this->createClub('A.S.C. Taekwondo-Hapkido', 'todo.gif', 'http://www.taekwondochelles.fr');
		$locationGDel   = $this->createLocation('Gymnase Delambre', 'avenue Delambre', 'Chelles', '77500', '77');
		$locationPomp   = $this->createLocation('Gymnase de Pomponne', 'rue cornouillers ', 'Pomponne', '77400', '77');
		$manager->persist($this->createLesson($club, $locationGDel, 'Taekwondo', 'Enfants (6-9 ans), blanches à orange-pourpre', 'monday', new \DateTime('18:30'), new \DateTime('19:30')));
		$manager->persist($this->createLesson($club, $locationGDel, 'Taekwondo', 'Enfants (10-12 ans), pourpre et +', 'monday', new \DateTime('19:30'), new \DateTime('20:30')));
		$manager->persist($this->createLesson($club, $locationGDel, 'Taekwondo, Taekwonkido', 'Adultes tous niveaux', 'monday', new \DateTime('20:15'), new \DateTime('22:00')));
		$manager->persist($this->createLesson($club, $locationGDel, 'Taekwondo', 'Enfants (6-9 ans), blanches à orange-pourpre', 'wednesday', new \DateTime('18:30'), new \DateTime('19:30')));
		$manager->persist($this->createLesson($club, $locationGDel, 'Taekwondo', 'Enfants (10-12 ans), pourpre et +', 'wednesday', new \DateTime('19:30'), new \DateTime('20:30')));
		$manager->persist($this->createLesson($club, $locationGDel, 'Taekwondo, Taekwonkido', 'Adultes tous niveaux', 'wednesday', new \DateTime('20:15'), new \DateTime('22:00')));
		$manager->persist($this->createLesson($club, $locationPomp, 'Taekwondo', 'Enfants (6-12 ans), blanches à orange-pourpre', 'thursday', new \DateTime('19:00'), new \DateTime('20:00')));
		$manager->persist($this->createLesson($club, $locationPomp, 'Taekwondo', 'Adultes (13 ans et +)', 'thursday', new \DateTime('19:00'), new \DateTime('20:30')));
		$manager->persist($this->createLesson($club, $locationPomp, 'Taekwonkido', 'Adultes (13 ans et +)', 'thursday', new \DateTime('20:30'), new \DateTime('21:00')));
		
	
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
	
	private function createLesson($club, $location, $discipline, $age_level, $day_of_week, $start_time, $end_time, $point = 1)
	{
		$lesson = new ClubLesson();
		$lesson->setClub($club);
		$lesson->setClubLocation($location);
		$lesson->setDiscipline($discipline);
		$lesson->setPoint($point);
		$lesson->setAgeLevel($age_level);
		$lesson->setDayOfWeek($day_of_week);
		$lesson->setStartTime($start_time);
		$lesson->setEndTime($end_time);
		return $lesson;
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

}
