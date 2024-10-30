<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::post('/loginapi', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('loginprosesapi');
Route::post('/refresh-token-api', [\App\Http\Controllers\Api\AuthController::class, 'refreshToken'])->name('refresh.token.api');

Route::middleware(['checkToken'])->group(function () {
  //Products
  Route::get('/products-role-api', [\App\Http\Controllers\Api\ProductController::class, 'role'])->name('products.role.api');
  Route::get('/products-data-show-api/{id}', [\App\Http\Controllers\Api\ProductController::class, 'getData'])->name('products.datashow.api');
  Route::get('/products-data-api', [\App\Http\Controllers\Api\ProductController::class, 'getProductsData'])->name('products.data.api');
  Route::post('/products-store-api', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('products.store.api');
  Route::post('/products-update/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('products.update.api');
  Route::delete('/products-delete/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('products.destroy.api');
  Route::post('/logout-api', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout.api');

  //User
  Route::get('/user-api', [\App\Http\Controllers\Api\UserController::class, 'getUser'])->name('user.api');
  Route::get('/user-data-api/{id}', [\App\Http\Controllers\Api\UserController::class, 'getUserData'])->name('user.data.api');
  Route::post('/user-upload-photo-api/{id}', [\App\Http\Controllers\Api\UserController::class, 'uploadImage'])->name('user.uploadimage.api');
  Route::post('/user-update-api/{id}', [\App\Http\Controllers\Api\UserController::class, 'updateUserProfile'])->name('user.update.api');
});

// Route::middleware(['auth:api'])->group(function () {
//     Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user(); 
});