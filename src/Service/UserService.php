<?php

namespace App\Service;

use App\Model\UserCreate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;


class UserService
{

    private $em;
    private $request;
    //private $validator;

	/**
	 * @param ObjectManager $em
	 * @param Request $request
	 */
	public function __construct(ObjectManager $em, Request $request) // , ValidatorInterface $validator
	{
		/*if($request->headers->get('X-DFA-Token') === null){
		throw new ApiException('Missing Token');
		}

		if($request->headers->get('X-DFA-Token') !== 'dfa'){
		throw new ApiException('Invalid Token');
		}*/

		$this->em = $em;
		$this->request = $request;
		//$this->validator = $validator;
	}
    
	public function create()
	{
		//$json = json_decode($request->getContent(), true);
		//$person = $serializer->deserialize($json, UserCreate::class, 'json');
		
		
		// See https://symfony.com/doc/current/validation/raw_values.html
		/*$constraint = new Assert\Collection([
			'lastname' => new Assert\Length(['min' => 1, 'max' => 255]),
			'firstname' => new Assert\Length(['min' => 1, 'max' => 255]),
			'email' => new Assert\Email(),
		]);*/
		
		//$violations = $validator->validate($input, $constraint, $groups);
		
		$propertyAccessor = PropertyAccess::createPropertyAccessor();
		
		//$day = \DateTime::createFromFormat('Y-m-d', $d);
		return array(
				'id' => '89',
				'holiday' => 'prout'
			);
	}
    
	public function __destruct()
	{
		$this->em = null;
		$this->request = null;
		$this->validator = null;
	}
    

}