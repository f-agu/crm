<?php

namespace App\DataFixtures;

use App\Entity\ClubLesson;
use App\Entity\User;
use App\Entity\UserLessonSubscribe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserLessonLinkFixtures extends Fixture implements OrderedFixtureInterface
{

	public function load(ObjectManager $manager)
	{
		$manager->persist($this->createUserLessonSubscribe(
				$this->getReference(AccountUserFixtures::TEACHER_USER_REFERENCE_PREFIX.'0'),
				$this->getReference('bry-tuesday-Hapkido-1945'),
				'TEACHER'));

		for ($i = 0; $i < 20; $i++)
		{
			$manager->persist($this->createUserLessonSubscribe(
					$this->getReference(AccountUserFixtures::STUDENT_USER_REFERENCE_PREFIX.$i),
					$this->getReference('bry-tuesday-Taekwonkido-1900'),
					'STUDENT'));
		}
		for ($i = 0; $i < 6; $i++)
		{
			$manager->persist($this->createUserLessonSubscribe(
					$this->getReference(AccountUserFixtures::STUDENT_USER_REFERENCE_PREFIX.$i),
					$this->getReference('bry-tuesday-Hapkido-1945'),
					'STUDENT'));
		}
		for ($i = 6; $i < 20; $i++)
		{
			$manager->persist($this->createUserLessonSubscribe(
					$this->getReference(AccountUserFixtures::STUDENT_USER_REFERENCE_PREFIX.$i),
					$this->getReference('bry-tuesday-Taekwondo-1945'),
					'STUDENT'));
		}

		$manager->flush();
	}

	public function getOrder()
	{
		return 300;
	}
	
	private function createUserLessonSubscribe($user, $lesson, $role)
	{
		$subsc = new UserLessonSubscribe();
		$subsc->setUser($user);
		$subsc->setLesson($lesson);
		$subsc->setRole($role);
		return $subsc;
	}

}
