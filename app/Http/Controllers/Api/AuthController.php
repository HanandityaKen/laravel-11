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

        if (!$accessToken = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('api')->user();

        // Menambahkan type ke payload saat membuat refresh token
        // $refreshToken = JWTAuth::fromUser($user, ['type' => 'refresh_token']);
        // $refreshToken = JWTAuth::fromUser($user); 
        $refreshToken = JWTAuth::refresh($accessToken);

        return response()->json([
            'user' => [
                'email' => auth()->guard('api')->user()->email,
                'name' => auth()->guard('api')->user()->name,
                'role' => auth()->guard('api')->user()->role,
            ],
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);

    }

    // public function refreshToken(Request $request)
    // {
    //     try { 

    //         $newToken = JWTAuth::parseToken()->refresh();

    //         return response()->json([
    //             'token'   => $newToken,
    //         ], 200);
            
    //     } catch (JWTException $e) {
    //         return response()->json([
    //             'message' => 'Token refresh failed'
    //         ], 400);
    //     }
    // }

    // public function refreshToken(Request $request)
    // {
    //     try {
    //         // Coba refresh token yang sudah kadaluarsa
    //         $newAccessToken = JWTAuth::parseToken()->refresh();

    //         return response()->json([
    //             'message' => 'refresh token tapi refresh_token masih ada',
    //             'access_token' => $newAccessToken,
    //         ], 200);

    //     } catch (TokenExpiredException $e) {
    //         try {
    //             // Jika access_token expired, gunakan refresh_token untuk generate ulang access_token
    //             $refreshToken = $request->header('Authorization-Refresh');
    //             JWTAuth::setToken($refreshToken);

    //             if (!$refreshToken || !JWTAuth::getPayload($refreshToken)->get('type') === 'refresh_token') {
    //                 return response()->json([
    //                     'message' => 'Invalid refresh token',
    //                 ], 401);
    //             }

    //             $newAccessToken = JWTAuth::parseToken()->refresh();

    //             return response()->json([
    //                 'access_token' => $newAccessToken,
    //             ], 200);

    //         } catch (TokenExpiredException $e) {
    //             // Jika refresh_token juga expired, arahkan ke halaman login
    //             return response()->json([
    //                 'message' => 'Refresh token expired, please log in again'
    //             ], 401);
    //         } catch (JWTException $e) {
    //             return response()->json([
    //                 'message' => 'Failed to refresh access token'
    //             ], 400);
    //         }
    //     } catch (JWTException $e) {
    //         return response()->json([
    //             'message' => 'Token refresh failed',
    //         ], 400);
    //     }
    // }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->bearerToken();
    
        if (!$refreshToken) {
            return response()->json([
                'message' => 'Token not provided',
            ], 401);
        }

        try {
            $newAccessToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'access_token' => $newAccessToken,
                'message' => 'acces token digenerate'
            ], 200);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'Refresh token expired, please log in again'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Failed to refresh access token',
            ], 400);
        }
    }


    public function logout(Request $request)
    {
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
