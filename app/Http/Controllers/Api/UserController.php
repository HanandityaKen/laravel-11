<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getUser() {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }

        $id = $user->id;

        return response()->json([
            'id' => $id
        ], 200);
    }

    public function getUserData(Request $request, $id) {
        $user =  User::findOrFail($id);

        return response()->json([
            'message' => 'Data dikirim',
            'user' => $user,
        ], 200);
    }

    public function uploadImage(Request $request, $id) {
        
        $request->validate([
            'photo' => 'image|mimes:jpeg,jpg,png',
        ]);

        $user = User::findOrFail($id);

        
        if ($user->photo) {
            Storage::delete('public/user/'.$user->photo);
        }

        $name = JWTAuth::getPayload()->get('name');

        $photo = $request->file('photo');
        $photoName = $name .'_'. Str::uuid() .'.' . $photo->getClientOriginalExtension();
        $photo->storeAs('public/user', $photoName);

        $user->photo = $photoName;
        $user->save();

        return response()->json([
            'message' => 'Photo berhasil di upload'
        ], 200);
    }

    public function updateUserProfile (Request $request, $id) {
        $user = User::findOrFail($id);

        $user->update([
            'email'     => $request->name,
            'password'     => $request->password,
        ]);

        return response()->json([
            'message' => 'Data berhasil diperbarui',
            'user'    => $user,
        ], 200);
    }
}
