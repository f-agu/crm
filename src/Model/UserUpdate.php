<?php
namespace App\Model;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="UserUpdate",
 *   required={"uuid", "lastname", "firstname", "birthday", "sex"}
 * )
 */
class UserUpdate extends UserCreate
{

	
}

