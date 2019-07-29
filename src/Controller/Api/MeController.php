<?php

namespace App\Controller\Api;

use primus852\ShortResponse\ShortResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class MeController extends AbstractController
{
	
    /**
     * @Route("/api/me", name="api_me_infos", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function infos()
    {
        $account = $this->getUser();
        $me = array();
        if($account) {
            $me['login'] = $account->getLogin();
            $me['roles'] = $account->getRoles();
            $user = $account->getUser();
            $me['uuid'] = $user->getUuid();
            $me['lastname'] = $user->getLastname();
            $me['firstname'] = $user->getFirstname();
            $me['sex'] = $user->getSex();
            $me['birthday'] = $user->getBirthday();
            $me['address'] = $user->getAddress();
            $me['zipcode'] = $user->getZipcode();
            $me['city'] = $user->getCity();
            $me['phone'] = $user->getPhone();
            $me['phone_emergency'] = $user->getPhoneEmergency();
            $me['nationality'] = $user->getNationality();
            $me['created'] = $user->getCreated();
            $me['mails'] = $user->getMails();
        }
        
        $me['granted_roles'] = array(
            'ROLE_SUPER_ADMIN' => $this->get('security.authorization_checker')->isGranted("ROLE_SUPER_ADMIN"),
            'ROLE_ADMIN' => $this->get('security.authorization_checker')->isGranted("ROLE_ADMIN"),
            'ROLE_TEACHER' => $this->get('security.authorization_checker')->isGranted("ROLE_TEACHER"),
            'ROLE_STUDENT' => $this->get('security.authorization_checker')->isGranted("ROLE_STUDENT"),
            'ROLE_USER' => $this->get('security.authorization_checker')->isGranted("ROLE_USER"),
            'IS_AUTHENTICATED_ANONYMOUSLY' => $this->get('security.authorization_checker')->isGranted("IS_AUTHENTICATED_ANONYMOUSLY")
        );
        return ShortResponse::success('me', $me);
    }
	
}
