<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\subCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use \App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use \App\Http\Controllers\ReviewController;
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

//Route::apiResource('role',RoleController::class);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('role',RoleController::class);
});
//Route::apiResource('role',RoleController::class)->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum','admin']], function () {
    // Routes that require admin role
    Route::apiResource('role',RoleController::class);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
//    Cart
    Route::delete('/cart/delete', [CartController::class, 'delete_all']);
    Route::apiResource('cart', CartController::class);


//    REVIEW
    Route::post('/review', [ReviewController::class, 'store_review']);
    Route::put('/review/product/{id}', [ReviewController::class, 'update_review']);
    Route::delete('/review/product/{id}', [ReviewController::class, 'delete_review']);


//    WISHLIST
    Route::delete('/wishlist/delete', [WishlistController::class, 'delete_all']);
    Route::apiResource('wishlist', WishlistController::class);
});



Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


//--------Packages && Package item-----------------------
Route::apiResource('packages',PackageController::class);
//Route::get('/packageitems/items/{packageId}', [PackageitemController::class, 'list_items']);
Route::apiResource('packageitems',PackageitemController::class);

Route::apiResource('order',OrderController::class);
Route::apiResource('orderItem',OrderItemController::class);

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('subCategories', subCategoryController::class);


// REVIEWS
Route::get('review/product/{id}', [ReviewController::class, 'list_review']);
//Route::apiResource('review', ReviewController::class);



















Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
