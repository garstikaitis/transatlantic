<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateUser() {
        try {
            $this->validateInput(request()->all(), [
                'userId' => 'required|integer|exists:users,id',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'onboardingCompleted' => 'required|boolean',
                'role' => 'required|string',
            ]);

            $onboardingCompleted = request('onboardingCompleted');
            if($onboardingCompleted === 'true') {
                $onboardingCompleted = true;
            } else {
                $onboardingCompleted = false;
            }

            $user = User::findOrFail(request('userId'));
            $user->firstName = request('firstName');
            $user->lastName = request('lastName');
            $user->email = request('email');

            $user->onboardingCompleted = $onboardingCompleted;
            $user->role = request('role');
            $user->save();

            if(request('newLogo') !== 'null') {
                $result = request()->file('newLogo')->storeOnCloudinaryAs('prod/' . request('userId'), 'image');
    
                $user->image = $result->getSecurePath();
                $user->save();
            }

            return response()->json(['success' => true, 'data' => $user], 200);

        } catch(Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
