<?php

use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\HomeController;


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

Route::group(['middleware' => ['auth:sanctum','admin']], function () {
    // Routes that require admin role
    Route::apiResource('roles',RoleController::class);
    Route::apiResource('packages',PackageController::class);
    Route::apiResource('packageitems',PackageitemController::class);
    // Route::apiResource('products', ProductController::class);
    // Route::apiResource('categories', CategoryController::class);
    // Route::apiResource('subcategories', subCategoryController::class);
    Route::get('/dashboard',[DashboardController::class,'analysis']);
});

Route::apiResource('products', ProductController::class);
Route::apiResource('subcategories', subCategoryController::class);
Route::apiResource('categories', CategoryController::class);


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
//    ORDER
    Route::apiResource('order',OrderController::class);
    Route::apiResource('orderItem',OrderItemController::class);

});




Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
//-------HOME-------
Route::get('/home/packages', [HomeController::class, 'Packages']);
Route::get('/home/products', [HomeController::class, 'Products']);
Route::get('/home/categories', [HomeController::class, 'Categories']);



// REVIEWS
Route::get('review/product/{id}', [ReviewController::class, 'list_review']);
//Route::apiResource('review', ReviewController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
