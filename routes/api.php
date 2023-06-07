<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\subCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageitemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::group(['middleware' => 'admin'], function () {
//     // Routes that require admin role
//     Route::apiResource('role',RoleController::class);
// });

Route::apiResource('role',RoleController::class);

Route::group(['middleware' => 'admin'], function () {
    // Routes that require admin role
});

Route::group(['middleware' => 'authenticated'], function () {
    // Routes that require any authenticated user
});
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//--------Packages && Packageitem-----------------------
Route::apiResource('packages',PackageController::class);
Route::get('/packageitems/{packageId}', [PackageitemController::class, 'index']);
Route::apiResource('packageitems',PackageitemController::class);

Route::apiResource('order',OrderController::class);
Route::apiResource('orderItem',OrderItemController::class);

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('subCategories', subCategoryController::class);
