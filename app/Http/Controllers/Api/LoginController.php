<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
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
            ],
            'token' => $token,
        ], 200);



        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // $credentials = $request->only('email', 'password');

        // //auth gagal
        // if (!$token = auth()->guard('api')->attempt($credentials)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Email atau Password Anda salah'
        //     ], 401);
        // }

        // //auth sukses
        // return response()->json([
        //     'success'   => true,
            // 'user'      => auth()->guard('api')->user(),
        //     'token'     => $token, 
        //     'redirect'  => route('products.index'),
        //     // 'redirect'  => url('/products'),
        // ], 200);


        // session()->put('token', $token);

        //  // Redirect ke products.index
        // return redirect()->route('products.index');
    }
}
