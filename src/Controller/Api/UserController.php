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
    public function listAll(Request $request, LoggerInterface $logger)
    {
        $user = $this->getUser();
        $data = array();
        if($this->get('security.authorization_checker')->isGranted("ROLE_ADMIN")) {
            $logger->info("granted admin");
            $data = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->findBy([], [
                    'lastname' => 'ASC',
                    'firstname' => 'ASC'
                ]);
        } elseif($this->get('security.authorization_checker')->isGranted("ROLE_TEACHER")) {
            $logger->info("granted teacher");
            $data = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->findInMyClubs($user->getId());
        } else {
            $logger->info("granted nothing");
        }
        $output = array();
        foreach ($data as &$u) {
            $uv = new UserView($u);
            array_push($output, $uv->jsonSerialize());
        }
        
        return ShortResponse::success('created', $output);
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
