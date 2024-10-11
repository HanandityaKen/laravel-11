<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('api')->user();

        return response()->json([
            'user' => [
                'email' => auth()->guard('api')->user()->email,
                'name' => auth()->guard('api')->user()->name,
                'role' => auth()->guard('api')->user()->role,
            ],
            'token' => $token,
        ], 200);

    }

    public function refreshToken(Request $request)
    {
        $token = $request->bearerToken();
    
        if (!$token) {
            return response()->json([
                'message' => 'Token not provided',
            ], 401);
        }

        try {
            $newToken = JWTAuth::parseToken($token)->refresh();

            return response()->json([
                'token' => $newToken,
                'message' => 'Access token generated successfully'
            ], 200);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'Refresh token expired, please log in again'
            ], 401);
        }

    }


    public function logout(Request $request)
    {
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
