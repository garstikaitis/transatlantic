<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function sayHi() {

        try {

            return response()->json(['success' => true, 'data' => 'Welcome to transatlantic API']);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }


    }
}
