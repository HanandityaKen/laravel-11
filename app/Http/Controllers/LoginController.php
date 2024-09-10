<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login()
    {
        return view('login');
    }
    

    public function loginProses(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

    
        // if (Auth::attempt($data)) {
        //     $request->session()->regenerate();
    
        //     return redirect()->intended('/products'); // Atau ke halaman yang diinginkan
        // }

        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->intended('/products');
            } elseif ($role === 'user') {
                return redirect()->intended('/user');
            } 
        }

        return back()->withErrors([
            'email' => 'email salah',
            'password' => 'password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Atau halaman yang diinginkan setelah logout
    }
}
