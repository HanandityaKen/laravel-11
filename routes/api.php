<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::post('/loginapi', App\Http\Controllers\Api\LoginController::class)->name('loginprosesapi');

Route::middleware(['auth:api'])->group(function () {
  Route::get('/products-api', [\App\Http\Controllers\Api\ProductController::class, 'role'])->name('products.index.api');
  Route::get('/products-data-api', [\App\Http\Controllers\Api\ProductController::class, 'getProductsData'])->name('products.data.api');
  Route::post('/logout-api', App\Http\Controllers\Api\LogoutController::class)->name('logout.api');


});

// Route::middleware(['auth:api'])->group(function () {
//     Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
// });

Route::middleware('auth:api', 'checkRole')->get('/user', function (Request $request) {
    return $request->user();
  // Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
  // Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
  // Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
  // Route::get('/products/{product}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
  // Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
  // Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
  // Route::get('/products-data', [\App\Http\Controllers\ProductController::class, 'getProductsData'])->name('products.data');
  
});