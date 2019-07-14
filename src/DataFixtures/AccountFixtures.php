<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture
{
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setLastname("admin");
        $user->setFirstname("admin");
        //$user->setBirthday(\DateTime::createFromFormat("yyyy-mm-dd", "2019-07-11"));
        $user->setBirthday(new \DateTime("11/07/2019"));

        $manager->persist($user);
        
        $account = new Account();
        $account->setUser($user);
        $account->setLogin("admin");
        $account->setPassword($this->passwordEncoder->encodePassword($account, 'admin'));        

        $manager->persist($account);

        $manager->flush();
    }
}
