<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;


// Login
Route::get('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logoutproses');

// Route::get('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
// Route::get('/main', [\App\Http\Controllers\LoginController::class, 'main']);
// Route::post('/login', [\App\Http\Controllers\LoginController::class, 'loginProses'])->name('loginproses');

Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
Route::get('/products-data', [\App\Http\Controllers\ProductController::class, 'getProductsData'])->name('products.data');
Route::get('/products-show/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');


Route::get('/profile-user/{id}', [\App\Http\Controllers\UserController::class, 'index'])->name('profile-user.index');






// // Rute produk yang dilindungi middleware checkRole
// Route::middleware(['auth','checkRole'])->group(function () {
//   Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
//   Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
// });


//kalo user dan admin beda halaman

// Admin-only routes
// Route::middleware(['auth', 'role:admin'])->group(function () {
//   Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
//   Route::get('/products/{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
//   Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
//   Route::put('/products/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
//   Route::delete('/products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
// });

// Route::middleware(['auth', 'role:admin'])->get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');

// //halaman product
// Route::middleware(['auth'])->group(function () {
  
//   Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
//   Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
//   //admin
//   // Route::middleware('role:admin')->group(function () {
//   //   Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
//   //   Route::get('/products/{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
//   //   Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
//   //   Route::put('/products/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
//   //   Route::delete('/products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
//   //   // Route::resource('/products', \App\Http\Controllers\ProductController::class);
//   //   // Route::resource('/products', \App\Http\Controllers\ProductController::class)->except(['index', 'show']);
  
//   // });
//   // user
//   // Route::middleware('role:user,admin')->group(function () {      
//     //   Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
//     //   Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
//     // });
//   });
  
// // Register
// Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
// Route::post('/register', [AuthController::class, 'store'])->name('auth.store');


