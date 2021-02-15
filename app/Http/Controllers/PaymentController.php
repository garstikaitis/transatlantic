<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Translation;

class PaymentController extends Controller
{
    public function getPaymentAmount() {

        try {

			$this->validateInput(request()->all(), [
				'organizationId' => 'required|integer|exists:organizations,id',
			]);

			$translationsTotal = Translation::where('organizationId', request('organizationId'))->count();

			$price = $translationsTotal / 100;

            return $price;

        } catch(Exception $e) {

            return $e->getMessage();

        }


    }
}
