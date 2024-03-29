<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subdomain',
        'logo'
    ];

    public function locales() {

        return $this->hasMany(Locale::class, 'id', 'localeId');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user', 'organizationId', 'userId')->withPivot('role', 'invitation_status');
    }
}
