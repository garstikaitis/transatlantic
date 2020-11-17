<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;

class RegisterController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        try {

			$credentials = request(['email', 'password']);
			$this->validateInput(
				$credentials,
				[
					'email' => 'required|string|min:6',
					'password' => 'required|string|min:6'
				]
			);
			
			$user = User::create($credentials);

			$authController = new AuthController();
			
			$token = auth()->login($user);
    
            return $authController->respondWithToken($token);

        } catch(Exception $e) {

			dd($e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error logging in'], 500);
        }
    }

}