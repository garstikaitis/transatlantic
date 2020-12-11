<?php

namespace App\Constants;

use Exception;

final class UserRoleConstants {
    const SUPERADMIN = 'SUPERADMIN';
	const COMPANY_ADMIN = 'COMPANY_ADMIN';
	const EDITOR = 'EDITOR';
	const VIEWER = 'VIEWER';
	
    private function __construct(){
        // throw an exception if someone can get in here (I'm paranoid)
        throw new Exception("Can't get an instance of " . self::class);
    }
}