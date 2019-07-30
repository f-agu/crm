<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\ViolationException;
use App\Model\UserCreate;
use App\Model\UserView;
use App\Service\UserService;
use App\Util\RequestUtil;
use Hateoas\HateoasBuilder;
use Hateoas\Representation\CollectionRepresentation;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use primus852\ShortResponse\ShortResponse;

class UserController extends AbstractController
{
	
    /**
     * @Route("/api/user", name="api_user_list-all", methods={"GET"})
     * @IsGranted("ROLE_TEACHER")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAll(LoggerInterface $logger)
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
        
        
        $userviews = array();
        foreach ($data as &$u) {
            array_push($userviews, new UserView($u));
        }
        $output = array('users' => $userviews);
        $hateoas = HateoasBuilder::create()->build();
        $json = json_decode($hateoas->serialize($output, 'json'));

        return new Response(json_encode($json), 200, array(
            'Content-Type' => 'application/hal+json'
        ));
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
            $output = array('user' => new UserView($data[0]));
        }
        
        $hateoas = HateoasBuilder::create()->build();
        $json = json_decode($hateoas->serialize($output, 'json'));
        
        return new Response(json_encode($json), 200, array(
            'Content-Type' => 'application/hal+json'
        ));
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
		    $user = $service->create($userCreate);
		} catch (\Exception $e) {
			return ShortResponse::exception('Query failed, please try again shortly ('.$e->getMessage().')');
		}
		
		$output = array('user' => new UserView($user));
		$hateoas = HateoasBuilder::create()->build();
		$json = json_decode($hateoas->serialize($output, 'json'));
		
		return new Response(json_encode($json), 200, array(
		    'Content-Type' => 'application/hal+json'
		));
	}
	
}
