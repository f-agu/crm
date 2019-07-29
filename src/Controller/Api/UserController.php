<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Model\UserCreate;
use App\Service\UserService;
use App\Util\RequestUtil;
use primus852\ShortResponse\ShortResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Exception\ViolationException;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use App\Model\UserView;

class UserController extends AbstractController
{
	
    /**
     * @Route("/api/user", name="api_user_list-all", methods={"GET"})
     * @IsGranted("ROLE_TEACHER")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAll()
    {
        $account = $this->getUser();
        $data = array();
        if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
            $data = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->findBy([], [
                    'lastname' => 'ASC',
                    'firstname' => 'ASC'
                ]);
        } elseif($this->get('security.authorization_checker')->isGranted("ROLE_TEACHER")) {
            $data = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->findInMyClubs($account->getId());
        } elseif($this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
            $data = array($account->getUser());
        }
        $output = array();
        foreach ($data as &$u) {
            $uv = new UserView($u);
            array_push($output, $uv->jsonSerialize());
        }
        
        return ShortResponse::success('listed', $output);
    }
    
    /**
     * @Route("/api/user/{uuid}", name="api_user_one", methods={"GET"})
     * @IsGranted("ROLE_TEACHER")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function one($uuid, LoggerInterface $logger)
    {
        $account = $this->getUser();
        $data = array();
        if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
            $data = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->findBy(['uuid' => $uuid]);
        } elseif($this->get('security.authorization_checker')->isGranted("ROLE_TEACHER")) {
            $data = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->findInMyClubs($account->getId(), $uuid);
        } elseif($this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
            $data = array($account->getUser());
        }
        $output = [];
        if(count($data) > 0) {
            $uv = new UserView($data[0]);
            $output = $uv->jsonSerialize();
        }
        
        return ShortResponse::success('getted', $output);
    }
    
    /**
 	 * @Route("/api/user", name="api_user_create-one", methods={"POST"})
	 * @IsGranted("ROLE_TEACHER")
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function createOne(Request $request, SerializerInterface $serializer, TranslatorInterface $translator)
	{
	    $requestUtil = new RequestUtil($serializer, $translator);
	   
	    try {
	       $userCreate = $requestUtil->validate($request, UserCreate::class);
	    } catch (ViolationException $e) {
	        return ShortResponse::error("data", $e->getErrors())
	           ->setStatusCode(Response::HTTP_BAD_REQUEST);
	    }
	    
		try {
			$service = new UserService($this->getDoctrine()->getManager(), $request);
		} catch (\Exception $e) {
			return ShortResponse::exception('Initialization failed, '.$e->getMessage());
		}
	
		try {
		    $data = $service->create($userCreate);
		} catch (\Exception $e) {
			return ShortResponse::exception('Query failed, please try again shortly ('.$e->getMessage().')');
		}

		return ShortResponse::success('created', $data);
	}	
}
