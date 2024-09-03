<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    // public function actionLogin(Request $request)
    // {
    //     $data = [
    //         'email' => $request->input('email'),
    //         'password' => $request->input('password')
    //     ];

    //     if (Auth::Attempt($data)) {
    //         return redirect('/products');
    //     } else{
    //         return redirect('/');
    //     }
    // }

    public function login()
    {
        return view('login');
    }

    public function loginProses(Request $request)
    {

        // $data = [
        //     'email' => $request->input('email'),
        //     'password' => $request->input('password')
        // ];


        // if (Auth::Attempt($data)) {
        //     return redirect('/');
        // } else{
        //     return redirect('/login');
        // }

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

    
        if (Auth::attempt($data)) {
            $request->session()->regenerate();
    
            return redirect()->intended('/dashboard'); // Atau ke halaman yang diinginkan
        }

        return back()->withErrors([
            'email' => 'username atau email salah',
        ]);
    }

    // public function store(Request $request)
    // {
    //     # code...
    // }

    // public function lgout(Request $request)
    // {
    //     # code...
    // }
}
