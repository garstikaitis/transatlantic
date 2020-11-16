<?php

namespace App\Traits;

use App\Classes\FormHelpers;
use Illuminate\Support\Facades\Validator;


trait ValidationTrait {

	public function validateInput(array $input, array $rules) {

	$validator = Validator::make($input, $rules, FormHelpers::validationMessages());
		
		FormHelpers::reportFormErrors($validator);
		
    }
	
}