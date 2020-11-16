<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transKey',
        'transValue',
        'localeId',
        'organizationId',
        'userId',
    ];

    public function locale() {

        return $this->hasOne(Locale::class, 'id', 'localeId');

    }
}
