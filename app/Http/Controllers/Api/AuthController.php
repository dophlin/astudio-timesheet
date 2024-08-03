<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Helper\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * Register a new User
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::Create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if($user) {
                return ResponseHelper::success(message: 'New user has been registered.', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'Unable to register the user.', statusCode: 400);
        } catch(Exception $e) {
            \Log::error('Unable to register the user. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to register the user.', statusCode: 500);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request) {
        try {
            if(!Auth::Attempt(['email' => $request->email, 'password' => $request->password])) {
                return ResponseHelper::error(message: 'Unable to login. Invalid credentials', statusCode: 401);
            }
            $user = Auth::user();
            $token = $user->createToken('web-token')->plainTextToken;
            $userAuth = [
                'user' => $user,
                'token' => $token
            ];
            return ResponseHelper::success(message: 'The user logged in successfully.', data: $userAuth, statusCode: 200);
        } catch(Exception $e) {
            \Log::error('Unable to login. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to login.', statusCode: 500);
        }
    }

    /**
    * Logout user
    */
    public function logout(Request $request) {
        try {
            $user = $request->user();

            if ($user) {
                $token = $user->currentAccessToken();

                if ($token) {
                    $token->delete();
                    return ResponseHelper::success(message: 'The user logged out successfully.', statusCode: 200);
                } else {
                    return ResponseHelper::error(message: 'No token found for the authenticated user.', statusCode: 401);
                }
            } else {
                return ResponseHelper::error(message: 'No authenticated user found.', statusCode: 401);
            }
        } catch (Exception $e) {
            \Log::error('Unable to logout. ' . $e->getMessage());
            return ResponseHelper::error(message: 'Unable to logout.', statusCode: 500);
        }
    }
}
