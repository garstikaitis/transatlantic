<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocaleProject extends Model
{
	use HasFactory;
	
	protected $table = 'locale_project';

    protected $fillable = [
		'projectId',
		'localeId'
	];

}
