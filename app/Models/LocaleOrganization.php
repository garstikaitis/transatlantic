<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocaleOrganization extends Model
{
    use HasFactory;

    protected $table = 'locale_organization';

    protected $fillable = [
        'localeId',
        'organizationId',
    ];
}
