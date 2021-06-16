<?php

namespace App\Enums;

use App\Interfaces\EnumInterface;

abstract class InvitationStatusEnum implements EnumInterface
{
    const PENDING = 'PENDING';
    const ACCEPTED = 'ACCEPTED';
    const EXPIRED = 'EXPIRED';

    public static function enumsToString()
    {
        return implode(',', [self::PENDING, self::ACCEPTED, self::EXPIRED]);
    }
}
