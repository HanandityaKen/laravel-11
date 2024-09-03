<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('products.index');
// });


// Login
// Route::get('/login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::get('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'loginProses'])->name('loginproses');

//halaman product
Route::get('/dashboard', [\App\Http\Controllers\ProductController::class, 'index']);
Route::resource('/products', \App\Http\Controllers\ProductController::class);

// // Register
// Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
// Route::post('/register', [AuthController::class, 'store'])->name('auth.store');


