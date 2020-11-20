<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProjectKey extends Model
{
	use HasFactory;
	
	protected $table = 'organization_project_key';

    protected $fillable = [
		'organizationId',
		'projectId',
		'key',
	];

}
