<?php

namespace App\Traits;

use App\Classes\FormHelpers;
use App\Models\OrganizationProjectKey;
use Illuminate\Support\Facades\Validator;


trait ValidationTrait {

	public function validateInput(array $input, array $rules) {

		$validator = Validator::make($input, $rules, FormHelpers::validationMessages());
		
		FormHelpers::reportFormErrors($validator);
		
	}
	
	public function validateToken() {

		$token = request('t');
		$key = OrganizationProjectKey::where('organizationId', request('o'))
			->where('projectId', request('p'))
			->where('key', $token)
			->first();
		if(!$key) {
			return abort(401, 'Token is not authorized for this project');
		}

	}
	
}