<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {

            $credentials = request(['email', 'password']);
    
            $shouldRespondWithToken =  auth()->attempt($credentials);
            if (!$shouldRespondWithToken) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
    
            return $this->respondWithToken($shouldRespondWithToken);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            return response()->json([
                'success' => true, 
                'data' => auth()->user()
            ]);
            
        } catch(Exception $e) {
            
            return response()->json(['success' => false]);

        }
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        try {

            auth()->logout();
                
            return response()->json(['success' => true, 'message' => 'Successfully logged out']);
        }

        catch(Exception $e) {
            
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => $user->load('organizations'),
            ]
        ], 200);
    }
}