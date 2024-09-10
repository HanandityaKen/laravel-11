<?php

use Illuminate\Support\Facades\Route;

// Login
// Route::get('/login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::get('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
// Route::get('/main', [\App\Http\Controllers\LoginController::class, 'main']);
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'loginProses'])->name('loginproses');
Route::post('/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logoutproses');


//halaman product
Route::middleware(['auth'])->group(function () {
  //admin
  Route::resource('/products', \App\Http\Controllers\ProductController::class);
  //user
  // Route::get('/user', [\App\Http\Controllers\UserController::class, 'index']);
});

// // Register
// Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
// Route::post('/register', [AuthController::class, 'store'])->name('auth.store');


