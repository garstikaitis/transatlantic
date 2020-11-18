<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
		'name',
		'organizationId'
	];
	
	public function organization() {
		return $this->belongsTo(Organization::class, 'organizationId');
	}

	public function locales() {
		return $this->belongsToMany(Locale::class, 'locale_project', 'projectId', 'localeId');
	}

	public function translations() {
		return $this->hasMany(Translation::class, 'projectId');
	}
}
