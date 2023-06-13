<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function analysis(){
        $users = User::all()->count();
        $products= Product::all()->count();
        $categories=Category::all()->count();
        $subCategories=Subcategory::all()->count();
        $orders=Order::all()->count();
        $processing_orders=Order::where('status','Processing')->count();
        $OnDelivery_orders=Order::where('status','On delivery')->count();
        $delivered = Order::where('status','Delivered')->count();
        return response()->json([
            'users' => $users,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'products'=>$products,
            'delivered' => $delivered,
            'orders' => $orders,
            'processing_orders' => $processing_orders,
            'OnDelivery_orders' => $OnDelivery_orders,
        ]);
    }
}
