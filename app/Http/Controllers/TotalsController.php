<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Http\Request;

class TotalsController extends Controller
{
    public function getDashboardTotals() {

        try {

			$this->validateInput(request()->all(), [
				'organizationId' => 'required|integer|exists:organizations,id'
			]);

            $totalTranslationsCount = Translation::where('organizationId', request('organizationId'))->count();
            
            $payment = (new PaymentController())->getPaymentAmount();

            return response()->json(['success' => true, 'data' => ['total_translations' => $totalTranslationsCount, 'payment' => $payment]]);

        } catch(Exception $e) {


            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }


    }
}
