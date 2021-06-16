<?php

namespace App\Enums;

use App\Interfaces\EnumInterface;

abstract class RoleEnum implements EnumInterface
{
    const VIEWER = 'VIEWER';
    const COMPANY_ADMIN = 'COMPANY_ADMIN';
    const EDITOR = 'EDITOR';
    const SUPERADMIN = 'SUPERADMIN';

    public static function enumsToString()
    {
        return implode(',', [self::VIEWER, self::COMPANY_ADMIN, self::EDITOR, self::SUPERADMIN]);
        return implode(',', [self::VIEWER, self::COMPANY_ADMIN, self::EDITOR, self::SUPERADMIN]);
    }
}
