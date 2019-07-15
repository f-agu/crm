<?php

namespace App;

class Role extends SplEnum {
	const __default = self::ANONYMOUS;
	
	const ROLE_ANONYMOUS = 'ROLE_ANONYMOUS';
	const ROLE_ADMIN = 'ROLE_ADMIN';
}