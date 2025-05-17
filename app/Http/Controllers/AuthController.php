<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponse;
    /**
     * Register a new user.
     *
     * This endpoint validates the user input, creates a new user,
     * generates an access token, and returns user details with the token.
     *
     * @param \App\Http\Requests\RegisterUserRequest $request The validated registration request.
     * @return \Illuminate\Http\JsonResponse The JSON response containing user data and access token.
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            $response = [
                'token' => $token,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
            return $this->successResponse($response,'User registered successfully.');
        } catch (\Throwable $ex) {
            Log::error("Error while register user: " . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong');        
        }
    }

    /**
     * Authenticate a user and issue an access token.
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.']
                ]);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            $response = [
                'token' => $token,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
    
            return $this->successResponse($response,'Login successful.');
        } catch (\Throwable $ex) {
            Log::error("Error while login the user: " . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong');
        }

    }
    
    /**
     * clear all the  token of the user.
     *
     * @param \App\Http\Requests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->currentAccessToken();
            $token->delete();
            return $this->successResponse([],'Logout Successfully.');
        } catch (\Throwable $ex) {
            Log::error("Error while logout the user: " . $ex->getMessage());
            return $this->errorResponse([],'Something went wrong');
        }
    }
}