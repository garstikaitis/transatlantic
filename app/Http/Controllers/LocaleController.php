<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use Exception;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function getAllLocales() {

        try {

            return response()->json(['success' => true, 'data' => Locale::all()]);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error fetching locales'], 500);

        }


    }
}
