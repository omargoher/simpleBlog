<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        
        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);

        $token = $user->createToken('api_token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token,
        ], 'User registered successfully', 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $user = User::where('email', $request->email)->first();
    
        if($user != null && Hash::check($request->password, $user->password))
        {
            $token = $user->createToken('api_token')->plainTextToken;

            return ApiResponse::success([
                'user' => $user,
                'token' => $token,
            ], 'Logged in successfully', 200);
        }
        else
        {
            return ApiResponse::error('Invalid credentials', null, 401);
        }
    }

    public function logout(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return ApiResponse::success([], 'Logout successfully', 200);
    }
}
