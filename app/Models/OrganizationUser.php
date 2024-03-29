<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table = 'organization_user';

    protected $fillable = [
        'userId',
        'organizationId',
        'role',
        'invitation_status'
    ];

    
}
