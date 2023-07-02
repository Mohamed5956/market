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
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatbotController;



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
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subcategories', subCategoryController::class);
    //    ORDER
    Route::apiResource('order',OrderController::class);
    Route::apiResource('orderItem',OrderItemController::class);
    //    Dashboard
    Route::get('/dashboard',[DashboardController::class,'analysis']);
    Route::get('/dashboard/most-sold',[DashboardController::class,'getMostSoldProducts']);
    Route::get('/dashboard/user-pay',[DashboardController::class,'getMostUserPay']);
    Route::get('/dashboard/order-status',[DashboardController::class,'getOrdersStatus']);

    //    Chatbot
    //    Route::apiResource('chatbot', ChatbotController::class);
    //-------Get ALL Users------
    Route::get('users', [UserController::class, 'index']);
    // DELETE USER
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    // Change user role
    Route::put('users/{id}/role', [UserController::class, 'update']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
//    Cart
    Route::delete('/cart/delete', [CartController::class, 'delete_all']);
    Route::get('/cart/show/count', [CartController::class, 'count_cart']);
    Route::apiResource('cart', CartController::class);
//    REVIEW
    Route::post('/review', [ReviewController::class, 'store_ review']);
    Route::patch('/review/product/{id}', [ReviewController::class, 'update_review']);
    Route::delete('/review/product/{id}', [ReviewController::class, 'delete_review']);
    Route::delete('/review/change/review_comment/{id}', [ReviewController::class, 'delete_review_comment']);
//    WISHLIST
    Route::delete('/wishlist/delete', [WishlistController::class, 'delete_all']);
    Route::apiResource('wishlist', WishlistController::class);
//    Store Order
    Route::post('/home/orders', [HomeController::class, 'store_order']);
    Route::apiResource('orderItem',OrderItemController::class);
    // Display user's order
    Route::get('/home/orders/{id}', [OrderController::class, 'user_order']);
    // Delete user's order
    Route::delete('/home/orders/{id}', [OrderController::class, 'destroy_order']);

    // Increment Product Quantity
    Route::patch('/inc/product/{product_id}/user/{user_id}', [ProductController::class, 'increment_prod_qty']);

    // Decrement Product Quantity
    Route::patch('/dec/product/{product_id}/user/{user_id}', [ProductController::class, 'decrement_prod_qty']);
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
//-------HOME-------
Route::get('/home/packages', [HomeController::class, 'Packages']);
Route::get('/home/products', [HomeController::class, 'Products']);
Route::get('/home/categories', [HomeController::class, 'Categories']);
Route::get('/home/subcategories/{categoryId}', [HomeController::class, 'Subcategories']);
Route::get('/home/packageitems/{packageId}', [HomeController::class, 'PackageItems']);

// REVIEWS
Route::get('review/product/{id}', [ReviewController::class, 'list_review']);
//Route::apiResource('review', ReviewController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//----------------------------------------------
//Route::apiResource('chatbot', ChatbotController::class);
Route::post('/chatbot', [ChatbotController::class, 'store']);
Route::get('/chatbot/language-options', [ChatbotController::class, 'getLanguageOptions']);
Route::post('/chatbot/language-selection', [ChatbotController::class, 'processLanguageSelection']);
Route::post('/chatbot/answer', [ChatbotController::class, 'processAnswer']);
Route::post('/chatbot/closechat', [ChatbotController::class, 'closeChat']);





Route::get('login/google', [AuthController::class,'googleRedirect']);
Route::get('login/google/callback',  [AuthController::class,'googleCallback']);
